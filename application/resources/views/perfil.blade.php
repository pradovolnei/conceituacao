<x-app-layout>
    <x-slot name="header">
    <li><a href="#">Usuários</a></li>
    <li><a href="perfil">Perfis</a></li>
    </x-slot>
    <div>
        
        
    </div>   
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('perfil.busca') }}" method="GET">
                        <input type="text" name="search" placeholder="Search Profiles">
                        <button type="submit">Search</button>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
