<?php

namespace App\Http\Livewire\Ampp\Billing;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Headless helper component for billing lines
 */
abstract class BillingLines extends Component
{
    public string $sessionKey = '';
    public ?int $selectedLine = null;
    public string $description = '';
    public array $lines = [];
    public float $totalPrice = 0;
    public float $discProductPrice = 0;
    public mixed $discPercentage= 0;
    public float $discPrice = 0;
    public bool $saved = true;
    public string $pdf = '';
    public array $products = [];

    protected array $rules = [
        'lines.*.order' => ['required'],
        'lines.*.text' => ['required', 'string', 'max:255'],
        'lines.*.price' => ['numeric', 'required_if:lines.*,type.text'],
        'lines.*.amount' => ['numeric', 'min:0', 'required_if:lines.*.type,text'],
        'lines.*.subtotal' => ['numeric', 'min:0', 'required_if:lines.*.type,text'],
        'lines.*.vat' => ['required_if:lines.*.type,text'],
    ];

    public function mount(string $sessionKey)
    {
        $this->sessionKey = $sessionKey;

        if (($cachedLines = session()->get($this->sessionKey)) != $this->lines && $cachedLines){
            $this->lines = $cachedLines;
            $this->saved = false;
        }

        $this->calcTotal();

        $this->products = Product::all()->toArray();
    }

    /*
     * Hooks
     */

    public function updatedLines()
    {
        $this->autoFillLines();
        $this->calcTotal();

        session([$this->sessionKey => $this->lines]);
    }

    // Discount modal
    public function updatedDiscPercentage()
    {
        if (!is_numeric($this->discProductPrice)){
            $this->discProductPrice = 0;
        }

        if (!is_numeric($this->discPercentage)){
            $this->discPercentage = 0;
        }

        $this->discPrice = $this->discProductPrice - ($this->discProductPrice * ($this->discPercentage / 100 ?? 0));
    }

    /*
     * Public functions
     */

    public function addLine($type)
    {
        $newLine = [
            'type' => $type,
            'order' => count($this->lines) + 1,
            'text' => ''
        ];

        if ($type == 'text'){
            $newLine['amount'] = 1;
            $newLine['vat'] = 21;
            $newLine['price'] = 0;
            $newLine['subtotal'] = 0;
            $newLine['discount'] = 0;
            $newLine['description'] = null;
        }

        array_push($this->lines, $newLine);
    }

    public function deleteLine($index)
    {
        unset($this->lines[$index]);

        $this->calcTotal();
    }

    public function resetCache()
    {
        session()->forget($this->sessionKey);

        $this->getLines();

        $this->saved = true;

        $this->calcTotal();
    }

    // Description modal
    #[On('openDescriptionModal')]
    public function openDescriptionModal($selectedLine)
    {
        $this->selectedLine = $selectedLine;
        $this->description = $this->lines[$selectedLine]['description'] ?? '';
    }

    public function saveDescription()
    {
        $this->lines[$this->selectedLine]['description'] = $this->description;
        $this->reset(['description', 'selectedLine']);
        session([$this->sessionKey => $this->lines]);

        $this->dispatch('closeDescriptionModal');
    }

    // Discount modal
    #[On('openDiscountModal')]
    public function openDiscountModal($selectedLine)
    {
        $this->selectedLine = $selectedLine;
        $this->discProductPrice = $this->lines[$selectedLine]['price'] ?? 0;
        $this->discPercentage = floatval($this->lines[$selectedLine]['discount']) ?? 0;
        $this->discPrice = $this->discProductPrice - ($this->discProductPrice * ($this->discPercentage / 100 ?? 0));
    }

    public function saveDiscount()
    {
        $this->lines[$this->selectedLine]['discount'] = $this->discPercentage;
        $this->lines[$this->selectedLine]['subtotal'] = $this->discProductPrice - ($this->discProductPrice * ($this->discPercentage / 100 ?? 0));
        $this->reset(['discProductPrice', 'discPercentage', 'discPrice', 'selectedLine']);
        session([$this->sessionKey => $this->lines]);

        $this->dispatch('closeDiscountModal');
        $this->autoFillLines();
        $this->calcTotal();
    }

    public function calcTotal($btw = false)
    {
        if (!$btw) {
            $this->totalPrice = 0;

            foreach ($this->lines as $line) {
                if ($line['type'] == 'text') {
                    $this->totalPrice += $line['subtotal'];
                }
            }

            return $this->totalPrice;
        }
        else{
            $totalAmountWithBtw = 0;
            foreach ($this->lines as $line){
                //if (($line['type'] != 'header') && $line['subtotal'] && $line['vat']) {
                if (($line['type'] != 'header') && $line['subtotal']) {
                    $totalAmountWithBtw += $line['subtotal'] * (($line['vat'] / 100) + 1);
                }
            }

            return $totalAmountWithBtw;
        }
    }

    public function autoFillLines()
    {
        foreach ($this->lines as &$line){
            if ($line['type'] == 'text'){
                $subtotal = (empty($line['price']) ? 0 : $line['price']) * (empty($line['amount']) ? 0 : $line['amount']);
                $subtotal -= $subtotal * ($line['discount'] / 100);

                $line['subtotal'] = $subtotal;
            }
        }
    }

    #[On('orderLines')]
    public function orderLines()
    {
        // This method is called when 'orderLines' event is dispatched
    }

    public function render(): View
    {
        return view('livewire.ampp.billing.billing-lines');
    }

    abstract function getLines();
}
