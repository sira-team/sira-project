<?php

declare(strict_types=1);

namespace Modules\Camp\Services;

use Modules\Camp\Models\CampEmailTemplate;

/**
 * Replaces {{ tag_name }} placeholders in email template subject and body.
 *
 * Usage:
 *   $replacer = new MergeTagReplacer;
 *   [$subject, $body] = $replacer->resolve($template, [
 *       'visitor_name' => 'Ahmed',
 *       'camp_name'    => 'Sommercamp 2026',
 *       ...
 *   ]);
 */
final class MergeTagReplacer
{
    /**
     * Replace merge tags in a raw string.
     *
     * @param  array<string, string>  $data  Keys without braces, e.g. ['visitor_name' => 'Ahmed']
     */
    public function replace(string $text, array $data): string
    {
        $search = array_map(fn (string $key): string => '{{ '.$key.' }}', array_keys($data));

        return str_replace($search, array_values($data), $text);
    }

    /**
     * Resolve both subject and body of a template in one call.
     *
     * @param  array<string, string>  $data
     * @return array{subject: string, body: string}
     */
    public function resolve(CampEmailTemplate $template, array $data): array
    {
        return [
            'subject' => $this->replace($template->subject, $data),
            'body' => $this->replace($template->body, $data),
        ];
    }
}
