<x-layout>
    <div>
        Count : {{ $count }}
    </div>
    <div>
        Time : {{ $time }} milliseconds
    </div>
    @foreach ($eventsInfo as $e)
    <div>
        {{ $e->type }} ({{ $e->date }}): {{ $e->description }}
    </div>
    @endforeach

    @foreach ($eventsWarning as $e)
    <div>
        {{ $e->type }} ({{ $e->date }}): {{ $e->description }}
    </div>
    @endforeach


    @foreach ($eventsAlert as $e)
    <div>
        {{ $e->type }} ({{ $e->date }}): {{ $e->description }}
    </div>
    @endforeach
</x-layout>
