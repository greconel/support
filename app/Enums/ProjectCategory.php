<?php

namespace App\Enums;

enum ProjectCategory: string
{
    /**
     * Sales
     */

    case Leads = 'leads';

    case VkkQuotePrep = 'VKK quote prep';

    case VkkOnderhandelingen = 'VKK onderhandelingen';

    case VkkOnHold = 'VKK on hold';

    /**
     * Uitvoering
     */

    case Audit = 'AUDIT';

    case Project = 'project';

    case Pipeline = 'pipeline';

    case ProjectOnHold = 'project on hold';

    case Service =  'service';

    case Intern = 'intern';

    public static function ForSelect(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $category) => [$category->value => $category->label()])
            ->sort()
            ->toArray();
    }

    public function label(): string
    {
        return match($this){
            self::Leads => __('Leads'),
            self::VkkQuotePrep => __('VKK quote prep'),
            self::VkkOnderhandelingen => __('VKK onderhandelingen'),
            self::VkkOnHold => __('VKK on hold'),
            self::Audit => __('AUDIT'),
            self::Project => __('PROJECT'),
            self::Pipeline => __('Pipeline'),
            self::ProjectOnHold => __('Proj. on hold'),
            self::Service => __('Service'),
            self::Intern => __('Intern'),
        };
    }

    public function description(): string
    {
        return match($this){
            self::Leads => __('Eerste gesprekken/leads waar nog geen inspanningen van het team voor nodig zijn (nog niet beslist of er een offerte gemaakt zal worden).'),
            self::VkkQuotePrep => __('Offerte voorbereiden (maken van offertedocument, inschatten van development uren, research voor offerte,…).'),
            self::VkkOnderhandelingen => __('Vanaf offerte verstuurd is en er enkel opvolging is van klanten feedback door sales, indien er aanpassingen worden gevraagd verschuift het project weer naar VKK QUOTE PREP.'),
            self::VkkOnHold => __('Offertes/leads die niet meer actief opgevolgd moeten worden maar binnen x tijd opnieuw gecontacteerd moeten worden (bv. dit jaar geen budget - volgend jaar opnieuw contact opnemen).'),
            self::Audit => __('AUDITs en analyses in uitvoering.'),
            self::Project => __('Project in uitvoering waar deze week of komende week iets voor moet gebeuren.'),
            self::Pipeline => __('Bevestigd project waar in de komende weken niks voor moet gebeuren maar wat wel zeker door gaat in de toekomst.'),
            self::ProjectOnHold => __('Project dat on hold staat en waar de toekomst onzeker over is (niet zeker of het nog tot pipeline behoort).'),
            self::Service => __('Actieve projecten waarvan serviceaanvragen kunnen binnenkomen / waarop onderhoud moet gebeuren.'),
            self::Intern => __('Interne projecten / eigen producten'),
        };
    }
}
