<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="p-4 font-mono text-sm bg-black text-white rounded-lg overflow-auto max-h-96 dark:border dark:border-gray-700">
        {!! $getState() !!}
    </div>
</x-dynamic-component> 