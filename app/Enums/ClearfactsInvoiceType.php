<?php

namespace App\Enums;

enum ClearfactsInvoiceType: string
{
    case Sale = 'SALE';
    case Purchase = 'PURCHASE';
}
