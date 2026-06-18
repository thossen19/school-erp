@props(['headers' => [], 'striped' => true, 'hover' => true, 'responsive' => true])
<div class="{{ $responsive ? 'table-responsive' : '' }}">
    <table class="table {{ $striped ? 'table-striped' : '' }} {{ $hover ? 'table-hover' : '' }} align-middle mb-0" {{ $attributes }}>
        @if(count($headers) > 0)
            <thead class="table-light">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>