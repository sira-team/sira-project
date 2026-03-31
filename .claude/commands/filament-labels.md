Scan all Filament PHP component files for missing label translations, fix them, then sync `lang/ar.json` and `lang/de.json`.

## Target directories

- `app/Filament`
- `Modules/Camp/app/Filament`
- `Modules/Expo/app/Filament`
- `Modules/Academy/app/Filament`

Only files that import at least one of these namespaces are relevant:
- `use Filament\Forms\Components`
- `use Filament\Infolists\Components`
- `use Filament\Schemas\Components`
- `use Filament\Tables\Columns`

## Step 1 — Run the scan script

```bash
bash .claude/scripts/scan-filament-labels.sh
```

Parse the output:
- Lines starting with `NEEDS_FIX:` list files where `->label()` calls are missing the `__()` wrapper **or where a labellable component has no `->label()` at all**.
- The block between `LABEL_STRINGS_START` and `LABEL_STRINGS_END` is the complete set of label strings already using `__()`.

## Step 2 — Fix files listed under NEEDS_FIX

For each file reported as `NEEDS_FIX`:

1. Read the file.
2. Find every `->label(...)` call where the argument is a bare string literal (single or double quoted) **without** `__()` wrapping.
3. Replace `->label('Some String')` → `->label(__('Some String'))` and `->label("Some String")` → `->label(__('Some String'))` (normalise to single quotes inside `__()`).
4. If a component has no `->label()` call at all, **add one** using the field name formatted as Title Case (e.g. `'notes'` → `->label(__('Notes'))`, `'hostel_id'` → `->label(__('Hostel'))`).
5. Write the file.
6. Add the newly wrapped strings to your in-memory list of label strings.

Skip any `->label()` where the argument is:
- already `__()` or `trans()`
- a variable (`$`)
- a closure (`fn` / `function`)
- a chained method call (e.g. `$record->label()`)
- empty (`->label()`)

## Step 3 — Run Pint on modified files

```bash
vendor/bin/sail bin pint --dirty --format agent
```

## Step 4 — Collect the full label string list

Re-run the scan script and capture the `LABEL_STRINGS_START … LABEL_STRINGS_END` block. This is the authoritative list of strings that must exist in both translation files.

## Step 5 — Read both translation files

Read `lang/ar.json` and `lang/de.json` in full. Keep the parsed contents in memory.

## Step 6 — Find and translate missing strings

For each string in the label list that is **absent as a key** in `lang/ar.json` or `lang/de.json`:

- Translate the English string to **Modern Standard Arabic** for `ar.json`.
- Translate the English string to **German** for `de.json`.
- Use a tone and register that matches the existing translations already present in each file (inspect a sample of existing pairs before translating).

Keep in mind common project-specific terms already translated in the files (e.g. "Camp", "Expo", "Academy", "Tenant") and stay consistent with how they are rendered.

## Step 7 — Write updated translation files

For each JSON file that needs changes:

1. Merge the new key-value pairs into the existing object.
2. **Sort all keys alphabetically** (case-sensitive, as JavaScript/PHP `ksort` would).
3. Write the file with 4-space indentation and a trailing newline.

Do not remove or change any existing translations — only add missing ones and re-sort.

## Step 8 — Verify

Run the scan script one final time and confirm that `NEEDS_FIX` lines no longer appear and all label strings are present as keys in both JSON files.