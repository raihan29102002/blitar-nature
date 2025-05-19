<div 
    x-data="{ show: false }" 
    x-effect="
        $wire.watch('trigger', () => {
            show = false;
            setTimeout(() => show = true, 10);
            setTimeout(() => show = false, 3000);
        })
    "
    x-show="show"
    x-transition
    class="fixed top-4 right-4 w-80 z-50"
>
    @php
        $typeConfig = [
            'success' => [
                'headerBg' => 'bg-green-500',
                'border'   => 'border-green-400',
                'bodyBg'   => 'bg-green-100',
                'text'     => 'text-green-700',
                'title'    => 'Sukses',
            ],
            'error' => [
                'headerBg' => 'bg-red-500',
                'border'   => 'border-red-400',
                'bodyBg'   => 'bg-red-100',
                'text'     => 'text-red-700',
                'title'    => 'Gagal',
            ],
            'warning' => [
                'headerBg' => 'bg-yellow-500',
                'border'   => 'border-yellow-400',
                'bodyBg'   => 'bg-yellow-100',
                'text'     => 'text-yellow-700',
                'title'    => 'Peringatan',
            ],
            'info' => [
                'headerBg' => 'bg-blue-500',
                'border'   => 'border-blue-400',
                'bodyBg'   => 'bg-blue-100',
                'text'     => 'text-blue-700',
                'title'    => 'Informasi',
            ],
        ];
        $config = $typeConfig[$type] ?? $typeConfig['info'];
    @endphp

    <div role="alert" class="rounded shadow-lg overflow-hidden">
        <div class="{{ $config['headerBg'] }} text-white font-bold px-4 py-2">
            {{ $config['title'] }}
        </div>
        <div class="border border-t-0 {{ $config['border'] }} {{ $config['bodyBg'] }} px-4 py-3 {{ $config['text'] }}">
            <p>{{ $message }}</p>
        </div>
    </div>
</div>
