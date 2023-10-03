<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Client') }}

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p>heer are list of your clients</p>
                @foreach($clients as $client)
                    <div class="py-3 text-gray-900">
                        <h3><b>Client ID:</b> {{ $client->id }}</h3>
                        <h3><b>Client Name:</b> {{ $client->name }}</h3>
                        <p><b>Client Link:</b> {{ $client->redirect }}</p>
                        <p><b>Client Secret:</b> {{ $client->secret }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="/oauth/clients" method="post">
                    @csrf
                    <div class="mt-6">
                        <label for="name">Name</label>
                        <input required type="text" name="name" placeholder="Client Name">
                    </div>
                    <div class="mt-6">
                        <label for="Redirect">Redirect</label>
                        <input required type="text" name="redirect" placeholder="Client's URL">
                    </div>
                    <button type="submit">Create Client</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
