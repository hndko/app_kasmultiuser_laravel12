@props([
    'headers' => [],
    'hasNumbering' => true,
    'numberingHeader' => '#',
])

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 shadow-xs">
    <div class="max-w-full overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/75 dark:bg-white/[0.02]">
                    @if ($hasNumbering)
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 w-14 text-center">
                            {{ $numberingHeader }}
                        </th>
                    @endif

                    @if (!empty($headers))
                        @foreach ($headers as $header)
                            @php
                                $headerText = is_array($header) ? ($header['text'] ?? $header['name'] ?? '') : $header;
                                $headerAlign = is_array($header) ? ($header['align'] ?? 'left') : 'left';
                                $alignClass = match($headerAlign) {
                                    'right' => 'text-right',
                                    'center' => 'text-center',
                                    default => 'text-left',
                                };
                            @endphp
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 {{ $alignClass }}">
                                {{ $headerText }}
                            </th>
                        @endforeach
                    @else
                        {{ $thead ?? '' }}
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if (isset($pagination))
        <div class="p-4 border-t border-gray-100 dark:border-gray-800">
            {{ $pagination }}
        </div>
    @endif
</div>
