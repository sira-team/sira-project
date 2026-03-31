#!/bin/bash
# scan-filament-labels.sh
# Scans Filament PHP component files for ->label() issues and reports:
#   NEEDS_FIX:<file>  — files where ->label() is missing the __() wrapper
#                       OR where a labellable component has no ->label() at all
#   LABEL_STRINGS_START / LABEL_STRINGS_END — all label strings wrapped in __()

set -uo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

SCAN_DIRS=(
    "app/Filament"
    "Modules/Camp/app/Filament"
    "Modules/Expo/app/Filament"
    "Modules/Academy/app/Filament"
)

all_label_strings=()

for dir in "${SCAN_DIRS[@]}"; do
    full_dir="$ROOT/$dir"
    [ -d "$full_dir" ] || continue

    while IFS= read -r file; do
        # Skip files that don't import any Filament component namespace
        if ! grep -qF 'use Filament\Forms\Components' "$file" && \
           ! grep -qF 'use Filament\Infolists\Components' "$file" && \
           ! grep -qF 'use Filament\Schemas\Components' "$file" && \
           ! grep -qF 'use Filament\Tables\Columns' "$file"; then
            continue
        fi

        # Find ->label( lines that are NOT already using __() or trans()
        # and are NOT dynamic (variables, closures, method calls)
        bad_lines=$(grep -nP -- '->label\(' "$file" \
            | grep -vP -- '->label\(__\(' \
            | grep -vP -- '->label\(trans\(' \
            | grep -vP -- '->label\(\)' \
            | grep -vP -- '->label\(\$' \
            | grep -vP -- '->label\(fn\b' \
            | grep -vP -- '->label\(function\b' \
            | grep -vP -- '->label\([a-zA-Z_][a-zA-Z0-9_]*->' \
            | grep -vP -- '->label\([a-zA-Z_][a-zA-Z0-9_]*\(' \
            || true)

        if [ -n "$bad_lines" ]; then
            echo "NEEDS_FIX:$file"
            while IFS= read -r line; do
                echo "  $line"
            done <<< "$bad_lines"
        fi

        # Collect all strings from ->label(__('...')) and ->label(__("..."))
        while IFS= read -r match; do
            [ -n "$match" ] && all_label_strings+=("$match")
        done < <(grep -oP -- "->label\(__\(['\"].*?['\"]\)\)" "$file" \
            | grep -oP -- "['\"].*?['\"]" \
            | sed "s/^['\"]//;s/['\"]$//" \
            || true)

    done < <(find "$full_dir" -name "*.php" -type f | sort)
done

# Second pass: detect labellable components that have NO ->label() call at all
python3 - "$ROOT" "${SCAN_DIRS[@]}" << 'PYEOF'
import re
import sys
import os

root = sys.argv[1]
scan_dirs = sys.argv[2:]

# Component types that must have a ->label() call
MUST_LABEL = {
    # Form fields
    'TextInput', 'Select', 'Textarea', 'DatePicker', 'TimePicker', 'DateTimePicker',
    'FileUpload', 'Toggle', 'Checkbox', 'Radio', 'CheckboxList',
    'RichEditor', 'MarkdownEditor', 'TagsInput', 'ColorPicker',
    # Infolist entries
    'TextEntry', 'IconEntry', 'ImageEntry', 'ColorEntry',
    # Table columns
    'TextColumn', 'BadgeColumn', 'ImageColumn', 'IconColumn',
    'ToggleColumn', 'SelectColumn', 'ColorColumn', 'CheckboxColumn',
}

FILAMENT_NS = [
    'use Filament\\Forms\\Components',
    'use Filament\\Infolists\\Components',
    'use Filament\\Schemas\\Components',
    'use Filament\\Tables\\Columns',
]

MAKE_RE = re.compile(r'([A-Z][a-zA-Z]+)::make\(')

for scan_dir in scan_dirs:
    full_dir = os.path.join(root, scan_dir)
    if not os.path.isdir(full_dir):
        continue

    for dirpath, _, filenames in os.walk(full_dir):
        for filename in sorted(filenames):
            if not filename.endswith('.php'):
                continue
            filepath = os.path.join(dirpath, filename)

            with open(filepath) as f:
                content = f.read()

            if not any(ns in content for ns in FILAMENT_NS):
                continue

            positions = [(m.start(), m.group(1)) for m in MAKE_RE.finditer(content)]

            missing = []
            for i, (pos, comp_type) in enumerate(positions):
                if comp_type not in MUST_LABEL:
                    continue

                # Chunk: from this make() to the next make() call (or EOF)
                end = positions[i + 1][0] if i + 1 < len(positions) else len(content)
                chunk = content[pos:end]

                if '->label(' not in chunk:
                    line = content[:pos].count('\n') + 1
                    missing.append(f"  line {line}: {comp_type}::make() missing ->label()")

            if missing:
                print(f"NEEDS_FIX:{filepath}")
                for m in missing:
                    print(m)
PYEOF

echo ""
echo "LABEL_STRINGS_START"
printf '%s\n' "${all_label_strings[@]}" | sort -u
echo "LABEL_STRINGS_END"