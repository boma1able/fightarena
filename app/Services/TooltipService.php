<?php

class TooltipService
{
    public static function makeTitleFromItem($item, array $labelsUa = [], array $rarityColors = []): string
    {
        $title = $item->name;

        // Рівень
        $level = $item->pivot->level ?? $item->level ?? 1;
        $title .= " [{$level}]\n";

        // Тип предмета для різних даних
        $slot = $item->slot ?? 'unknown';

        // Міцність (якщо є)
        $currentDurability = $item->pivot->current_durability ?? null;
        $maxDurability = $item->pivot->max_durability ?? null;
        if ($currentDurability !== null && $maxDurability !== null) {
            $title .= "Міцність: {$currentDurability} / {$maxDurability}\n";
        }

        // Для зброї — урон
        if ($slot === 'weapon') {
            $minDamage = $item->pivot->min_damage ?? $item->min_damage ?? 0;
            $maxDamage = $item->pivot->max_damage ?? $item->max_damage ?? 0;
            $title .= "Урон: {$minDamage}–{$maxDamage}\n";
        }

        // Для броні — броня по зонах
        if (in_array($slot, ['helmet', 'armor', 'legs', 'boots', 'shield'])) {
            $defense = $item->defense_by_zone ?? [];
            if (is_string($defense)) {
                $defense = json_decode($defense, true) ?? [];
            }
            foreach ($defense as $zone => $range) {
                $label = $labelsUa[$zone] ?? ucfirst($zone);
                $title .= "Броня {$label}: {$range['min']} – {$range['max']}\n";
            }
        }

        // Бонуси
        $bonuses = $item->pivot->bonuses ?? $item->bonuses ?? [];
        if (is_string($bonuses)) {
            $bonuses = json_decode($bonuses, true) ?? [];
        }
        foreach ($bonuses as $stat => $value) {
            $label = $labelsUa[$stat] ?? ucfirst($stat);
            $title .= "{$label}: +{$value}\n";
        }

        // Опис
        if (!empty($item->description)) {
            $title .= "\n" . $item->description;
        }

        return trim($title);
    }
}
