<x-filament::widget>
    <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
        <div class="flex items-center gap-2">
            <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-danger-500" />
            <span class="text-sm font-medium">Litiges non traités :</span>
        </div>
        <span class="text-xl font-bold text-danger-600">{{ $count }}</span>
    </div>
    <script>
        setInterval(function() {
            @this.refresh();
        }, 30000); // rafraîchit toutes les 30 secondes
    </script>
</x-filament::widget>