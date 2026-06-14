<?php

declare(strict_types=1);

namespace Proof\Settings;

defined('ABSPATH') || exit;

/**
 * Single source of truth for reading, defaulting and sanitising the
 * `proof_settings` option. Both the admin screen and the front end read through
 * here so defaults and validation never drift.
 */
final class SettingsRepository
{
    public const OPTION = 'proof_settings';

    /** @var array<string, mixed>|null */
    private ?array $cache = null;

    /**
     * Resolved settings: stored values merged over packaged defaults.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $stored = get_option(self::OPTION, []);
        if (! is_array($stored)) {
            $stored = [];
        }

        return $this->cache = array_merge($this->defaults(), $stored);
    }

    public function get(string $key): mixed
    {
        return $this->all()[$key] ?? null;
    }

    /**
     * Packaged defaults.
     *
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        /** @var array<string, mixed> $defaults */
        $defaults = require PROOF_DIR . 'config/defaults.php';

        return $defaults;
    }

    /**
     * Sanitise submitted settings before save. Unknown keys are dropped; every
     * value is clamped or whitelisted; defaults backfill anything missing.
     *
     * @param mixed $raw
     * @return array<string, mixed>
     */
    public function sanitize(mixed $raw): array
    {
        if (! is_array($raw)) {
            $raw = [];
        }

        $defaults = $this->defaults();

        $position = isset($raw['position']) ? sanitize_key((string) $raw['position']) : 'bottom-left';
        if (! in_array($position, ['bottom-left', 'bottom-right', 'top-left', 'top-right'], true)) {
            $position = 'bottom-left';
        }

        $anonName = isset($raw['anonymous_name_text'])
            ? sanitize_text_field((string) $raw['anonymous_name_text'])
            : '';
        if ($anonName === '') {
            $anonName = (string) $defaults['anonymous_name_text'];
        }

        $clean = [
            'enabled'             => ! empty($raw['enabled']),
            'position'            => $position,
            'anonymous_name_text' => $anonName,
            'initial_delay'       => $this->clampInt($raw, 'initial_delay', 0, 120, (int) $defaults['initial_delay']),
            'display_time'        => $this->clampInt($raw, 'display_time', 2, 60, (int) $defaults['display_time']),
            'interval'            => $this->clampInt($raw, 'interval', 3, 600, (int) $defaults['interval']),
        ];

        $this->cache = null;

        return array_merge($defaults, $clean);
    }

    /**
     * @param array<string, mixed> $raw
     */
    private function clampInt(array $raw, string $key, int $min, int $max, int $fallback): int
    {
        $value = isset($raw[$key]) ? (int) $raw[$key] : $fallback;

        return max($min, min($max, $value));
    }
}
