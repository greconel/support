<?php

namespace App\Http\Livewire\Ampp\QrCodes;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use LaraZeus\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Create extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $notes = '';

    public string $qrCode = '';
    public string $contents = '';
    public string $size = '100';

    // format
    public string $format = 'png';
    public array $formats = ['png' => 'png', 'svg' => 'svg', 'eps' => 'eps'];

    // style
    public string $style = 'square';
    public array $styles = ['square' => 'square', 'dot' => 'dot', 'round' => 'round'];

    // color
    public array $color = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 100];
    public array $backgroundColor = ['r' => 255, 'g' => 255, 'b' => 255, 'a' => 100];

    // gradient
    public string $gradientType = 'vertical';
    public array $gradientTypes = ['vertical' => 'vertical', 'horizontal' => 'horizontal', 'diagonal' => 'diagonal', 'inverse_diagonal' => 'inverse_diagonal', 'radial' => 'radial'];
    public array $gradientStartColor = ['r' => 0, 'g' => 0, 'b' => 0];
    public array $gradientEndColor = ['r' => 0, 'g' => 0, 'b' => 0];

    // eyes
    public string $eyeStyle = 'square';
    public array $eyeStyles = ['square' => 'square', 'circle' => 'circle'];
    public array $firstEyeInnerColor = ['r' => 0, 'g' => 0, 'b' => 0];
    public array $firstEyeOuterColor = ['r' => 0, 'g' => 0, 'b' => 0];
    public array $secondEyeInnerColor = ['r' => 0, 'g' => 0, 'b' => 0];
    public array $secondEyeOuterColor = ['r' => 0, 'g' => 0, 'b' => 0];
    public array $thirdEyeInnerColor = ['r' => 0, 'g' => 0, 'b' => 0];
    public array $thirdEyeOuterColor = ['r' => 0, 'g' => 0, 'b' => 0];

    // error correction
    public string $errorCorrection = 'L';
    public array $errorCorrections = ['L' => 'L - 7%', 'M' => 'M - 15%', 'Q' => 'Q - 25%', 'H' => 'H - 30%'];

    // image
    public $image;
    public string $imageSize = '30';

    public function updated()
    {
        $this->size = empty($this->size) ? 1 : $this->size;
        $this->imageSize = empty($this->imageSize) ? 0 : $this->imageSize;
        $this->generate();
    }

    public function generate()
    {
        $this->qrCode = base64_encode($this->createQrCode()?->generate($this->contents));
    }

    public function download(): BinaryFileResponse
    {
        $qrCode = $this->createQrCode($this->format);

        $filePath = storage_path('app/livewire-tmp/' . time() . '.' . $this->format);

        $qrCode->generate($this->contents, $filePath);

        return response()->download($filePath, 'QrCode.' . $this->format);
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $file =  $this->createQrCode($this->format);
        $filePath = storage_path('app/livewire-tmp/' . time() . '.' . $this->format);
        $file->generate($this->contents, $filePath);

        $qrCode = \App\Models\QrCode::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'notes' => $this->notes
        ]);

        $qrCode->addMedia($filePath)
            ->usingName('qrCode.' . $this->format)
            ->toMediaCollection('image', 'private');

        return redirect()->action(\App\Http\Controllers\Ampp\QrCodes\IndexQrCodeController::class);
    }

    public function render(): View
    {
        return view('livewire.ampp.qr-codes.create');
    }

    private function createQrCode($format = 'png')
    {
        if (!$this->contents) {
            return null;
        }

        $qrCode = QrCode::format($format)
            ->style($this->style)
            ->size($this->size)
            ->color($this->color['r'], $this->color['g'], $this->color['b'], $this->color['a'])
            ->backgroundColor($this->backgroundColor['r'], $this->backgroundColor['g'], $this->backgroundColor['b'], $this->backgroundColor['a'])
            ->gradient($this->gradientStartColor['r'], $this->gradientStartColor['g'], $this->gradientStartColor['b'], $this->gradientEndColor['r'], $this->gradientEndColor['g'], $this->gradientEndColor['b'], $this->gradientType)
            ->eye($this->eyeStyle)
            ->eyeColor(0, $this->firstEyeOuterColor['r'], $this->firstEyeOuterColor['g'], $this->firstEyeOuterColor['b'], $this->firstEyeInnerColor['r'], $this->firstEyeInnerColor['g'], $this->firstEyeInnerColor['b'])
            ->eyeColor(1, $this->secondEyeOuterColor['r'], $this->secondEyeOuterColor['g'], $this->secondEyeOuterColor['b'], $this->secondEyeInnerColor['r'], $this->secondEyeInnerColor['g'], $this->secondEyeInnerColor['b'])
            ->eyeColor(2, $this->thirdEyeOuterColor['r'], $this->thirdEyeOuterColor['g'], $this->thirdEyeOuterColor['b'], $this->thirdEyeInnerColor['r'], $this->thirdEyeInnerColor['g'], $this->thirdEyeInnerColor['b'])
            ->errorCorrection($this->errorCorrection);

        if ($this->image){
            $qrCode = $qrCode->merge($this->image->getRealPath(), ($this->imageSize / 100), true);
        }

        return $qrCode;
    }
}
