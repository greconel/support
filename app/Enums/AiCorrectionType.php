<?php

namespace App\Enums;

enum AiCorrectionType: string
{
    case ImpactOnly = 'impact_only';
    case LabelsOnly = 'labels_only';
    case Both = 'both';

    public function label(): string
    {
        return match ($this){
            self::ImpactOnly => __('Impact only'),
            self::LabelsOnly => __('Labels only'),
            self::Both => __('Both'),
        };
    }
}
