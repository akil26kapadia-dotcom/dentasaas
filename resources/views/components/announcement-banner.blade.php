@php
    $announcements = \App\Models\Announcement::live()->latest()->get();

    $styles = [
        'info' => ['bg' => 'bg-blue-light-50', 'text' => 'text-blue-light-600', 'icon' => 'fa-circle-info'],
        'success' => ['bg' => 'bg-success-50', 'text' => 'text-success-600', 'icon' => 'fa-circle-check'],
        'warning' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-600', 'icon' => 'fa-triangle-exclamation'],
        'danger' => ['bg' => 'bg-error-50', 'text' => 'text-error-600', 'icon' => 'fa-circle-exclamation'],
    ];
@endphp

@if ($announcements->isNotEmpty())
    <div x-data="{
            dismissed: JSON.parse(localStorage.getItem('dismissedAnnouncements') || '[]'),
            dismiss(id) {
                this.dismissed.push(id);
                localStorage.setItem('dismissedAnnouncements', JSON.stringify(this.dismissed));
            }
         }">
        @foreach ($announcements as $announcement)
            @php $style = $styles[$announcement->type] ?? $styles['info']; @endphp
            <div x-show="! dismissed.includes({{ $announcement->id }})" x-cloak
                 class="flex items-start gap-3 {{ $style['bg'] }} px-4 sm:px-6 lg:px-8 py-3">
                <i class="fa-solid {{ $style['icon'] }} {{ $style['text'] }} mt-0.5"></i>
                <div class="flex-1 min-w-0 text-sm">
                    <span class="font-semibold {{ $style['text'] }}">{{ $announcement->title }}</span>
                    <span class="text-gray-600">— {{ $announcement->body }}</span>
                </div>
                <button type="button" @click="dismiss({{ $announcement->id }})" class="{{ $style['text'] }} hover:opacity-70">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endforeach
    </div>
@endif
