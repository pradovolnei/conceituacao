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
                        @if (count($results) > 0)
                            <ul>
                                <br>
                                <table width= "467" border-width= "1px" >
                                        <tr>
                                            <td>
                                                Cód ID
                                            </td>
                                            <td>
                                                Nome Perfil
                                            </td>
                                            <td>
                                                Ind Admin
                                            </td>
                                            @foreach ($results as $result)
                                                <tr>
                                                    <td>
                                                        {{ $result->id  }}
                                                    </td>
                                                    <td>
                                                        <a name= "idProfile"  value= {{ $result->id }}  </a> {{ $result->nameProfile  }}
                                                    </td>
                                                    <td>
                                                        {{ $result->admin  }}
                                                    </td>
                                                </tr>
                                            
                                            @endforeach
                                </table>
                            </ul>
                        @else
                            <p>No results found.</p>
                        @endif
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
