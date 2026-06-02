@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- Mobile --}}
        <div class="flex items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-white  rounded-lg cursor-not-allowed">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white  rounded-lg hover:bg-gray-50 transition">
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white  rounded-lg hover:bg-gray-50 transition">
                    Next
                </a>
            @else
                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-white  rounded-lg cursor-not-allowed">
                    Next
                </span>
            @endif

        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-600">
                    Showing
                    @if ($paginator->firstItem())
                        <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    of
                    <span class="font-semibold">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <div>
                <div class="inline-flex items-center overflow-hidden rounded-xl  bg-white shadow-sm">

                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center justify-center px-3 py-2 text-gray-400 cursor-not-allowed  ">
                            <x-lucide-chevron-left class="w-4 h-4" />
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                           rel="prev"
                           aria-label="{{ __('pagination.previous') }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-gray-600   hover:bg-gray-50 transition">
                            <x-lucide-chevron-left class="w-4 h-4" />
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)

                        {{-- Separator --}}
                        @if (is_string($element))
                            <span class="px-4 py-2 text-sm text-gray-400  ">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)

                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-primary rounded-full">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                       class="px-4 py-2 text-sm font-medium text-gray-700   hover:bg-gray-50 transition">
                                        {{ $page }}
                                    </a>
                                @endif

                            @endforeach
                        @endif

                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                           rel="next"
                           aria-label="{{ __('pagination.next') }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-gray-600 hover:bg-gray-50 transition">
                            <x-lucide-chevron-right class="w-4 h-4 text-primary" />
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center px-3 py-2 text-gray-400 cursor-not-allowed">
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </span>
                    @endif

                </div>
            </div>

        </div>
    </nav>
@endif