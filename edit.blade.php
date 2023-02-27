@extends('layouts.principal')

@section('title')
<i class="fa fa-object-group"></i> @isset($caixa->id) WMS - Caixa: {{ $caixa->id }} @else WMS - Nova Caixa @endisset
@append

@section('conteudo')

@foreach(['danger', 'warning', 'success', 'info'] as $tipo)
    @if(session("alert-$tipo"))
        <p class="alert alert-{{$tipo}}" role="alert">{{ session("alert-$tipo") }}</p>
    @endif
@endforeach

    <div class="row">
        <div class="col-md-9">
            <form method="POST" @if(Route::currentRouteName() == 'caixa.create') action="{{ route('caixa.store') }}" @elseif(isset($caixa)) action="{{ route('caixa.update', ['id' => $caixa->id]) }}" @endif>
                @csrf
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-cube"></i> <span>Dados da Caixa</span></h3>
                        <div class="form-group">
                            <label class="col-md-2 control-label"><span class="pull-left">Filtro</span></label>
                            <div class="col-md-4">
                                <select class="form-control input-sm" name="filtro" @if(Route::currentRouteName() == 'caixa.show') disabled @endif>
                                    <option value="" hidden>Selecione um local ... </option>
                                    @foreach($locais as $local)
                                    <option value="{{ $local->id }}" @if(isset($caixa) && $caixa->id_local == $local->id) selected @endif>{{ $local->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">

                        <div class="row">
                            
                            <div class="col-md-8">

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-top: 12px;">
                                            <label class="col-md-3 control-label"><span class="pull-left"
                                                    style="margin-top: 4px;">Descrição</span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="descricao" class="form-control input-sm" @isset($caixa) value="{{ $caixa->descricao }}" @endisset
                                                @if(Route::currentRouteName() == 'caixa.show') readonly @endif>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-top: 12px;">
                                            <label class="col-md-3 control-label"><span class="pull-left"
                                                    style="margin-top: 4px;">Endereço</span></label>
                                            <div class="col-md-3">
                                                <input type="text" name="rua" class="form-control input-sm" placeholder="RUA" @isset($caixa) value="{{ $caixa->rua }}" @endisset
                                                @if(Route::currentRouteName() == 'caixa.show') readonly @endif>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="prateleira" class="form-control input-sm" placeholder="PRATELEIRA" @isset($caixa) value="{{ $caixa->prateleira }}" @endisset
                                                @if(Route::currentRouteName() == 'caixa.show') readonly @endif>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="nivel" class="form-control input-sm" placeholder="NÍVEL" @isset($caixa) value="{{ $caixa->nivel }}" @endisset
                                                @if(Route::currentRouteName() == 'caixa.show') readonly @endif>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-top: 12px;">
                                            <label class="col-md-3 control-label"><span class="pull-left"
                                                    style="margin-top: 4px;">Grifes</span></label>
                                            <div class="col-md-9">
                                                @if(Route::currentRouteName() == 'caixa.show')
                                                    <input type="text" class="form-control input-sm" value="@foreach($caixa->grifes as $i => $grife)@if($i + 1 == $caixa->grifes->count()){{ $grife->pivot->codgrife }}@else{{ $grife->pivot->codgrife . ',' }}@endif @endforeach" readonly>
                                                @else
                                                    <select id="grifes" multiple="multiple" name="grifes[]">
                                                        @foreach($grifes as $grife)
                                                            <option value="{{$grife->codigo}}" @if(isset($caixa) && $caixa->grifes()->where('codgrife', $grife->codigo)->exists()) selected @elseif(isset($caixa)) @else selected @endif>{{$grife->codigo}} - {{$grife->descricao}}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            
                            <div class="col-md-4">

                                <div class="row">
                                    <div class="col-md-12">

                                        <li class="list-group-item" style="margin-top: 10px;">
                                            <b>Limitar peças</b>
                                            <div class="TriSea-technologies-Switch pull-right">
                                                <input class="limitar" id="TriSeaDefault" name="limitar" type="checkbox" @if(isset($caixa) && $caixa->limitar == true) checked @endif
                                                @if(Route::currentRouteName() == 'caixa.show') disabled @endif>
                                                <label for="TriSeaDefault" class="label-default"></label>
                                            </div>
                                            <div>
                                                <div style="display: flex; margin-top: 5px;">
                                                    <input type="number" id="limite" name="limite" class="form-control input-sm" @isset($caixa) value="{{ $caixa->limite }}" @else value="30" @endif style="margin-right: 25px;" @if(isset($caixa) && $caixa->limitar == true) @else disabled @endif
                                                    @if(Route::currentRouteName() == 'caixa.show') readonly @endif>
                                                    <span style="margin-top: 4px; margin-right: 5px;">Peças</span>
                                                </div>
                                            </div>
                                        </li>
                                        
                                    </div>
                                    @isset($caixa)
                                        <div class="col-md-12"> 
                                            <div style="width: 100%;height: 75px;background-color: #ff7f505c; margin-top: 12px; border-radius: 5px;">
                                                <div style="display: flex;justify-content: center;padding-top: 3px;">
                                                    <span style="font-weight: 600;font-size: 13px;">CONTADOR DE ITENS</span>
                                                </div>
                                                <div style="display: flex; justify-content: center;">
                                                    <var style="font-weight: bolder;font-size: 48px;margin-top: -11px;">{{ $caixa->alocacoes->sum('qtde') ?? 0 }}</var>
                                                </div>
                                            </div>
                                        </div>
                                    @endisset
                                </div>

                            </div>

                        </div>

                        

                    </div>

                    <div class="box-footer">
                        @if(Route::currentRouteName() == 'caixa.edit' || Route::currentRouteName() == 'caixa.create')
                            <button type="submit" class="btn btn-primary pull-right">Salvar</button>
                        @elseif(isset($caixa))
                            <a href="{{ route('caixa.edit', ['id' => $caixa->id])}}" data-id_caixa="" class="btn btn-default pull-right"><i class="fa fa-edit"></i> Editar</a>
                        @endif
                    </div>

                </div>
            </form>
        </div>
        
        <div class="col-md-3">
            <form action="" method="post" class="form-horizontal">
                @csrf

                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-gears"></i> Controle</h3>
                    </div>
                    <div class="box-body">

                        <div class="form-group">

                            <label class="col-md-3 control-label">Caixa</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" readonly="" name="id_caixa" @isset($caixa) value="{{$caixa->id}}" @endisset>
                            </div>

                        </div>


                        <div class="form-group">

                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>

                        </div>

                    </div>
                    <div class="box-footer">

                        @isset($caixa)
                            <a target="_blank" href="/logistica/alocacoes/etiqueta/caixa/{{ $caixa->id }}" class="btn btn-info btn-flat pull-left">Etiq. Caixa</a>
                        @endisset
                        @if(Route::currentRouteName() != 'caixa.create')
                            <a href="{{ route('caixa.create') }}" class="btn btn-primary btn-flat pull-right">Nova Caixa</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-12">
            @isset($caixa)
                <form class="form-horizontal" action="/logistica/alocacoes/entrada" method="post" id="nova-entrada-alocacao">
                    @csrf
                    <div class="box box-widget">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-plus"></i> Nova Entrada</h3>
                        </div>

                        <div class="box-body">
                            @isset($caixa)
                                <input type="hidden" name="id_caixa" value="{{ $caixa->id }}">
                            @endisset

                            <div class="row">

                                <div class="col-md-5" style="height: 310px;display: flex;align-items: center;">

                                    <div style="margin-top: -25px;">
                                        <div class="col-md-8">
                                            <label>Referência</label>
                                            <input id="item-caixa" type="text" name="item" class="form-control input-lg" autofocus=""
                                                placeholder="Referência" required="" onkeydown="return event.keyCode != 9;">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Quantidade</label>
                                            <input type="text" name="qtde" class="form-control input-lg" placeholder="Quantidade"
                                                value="1" required="" onkeydown="return event.keyCode != 9;">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="labels">
                                        <div>
                                            <label>Descrição da Peça</label>
                                            <input type="text" class="form-control input-sm" id="descricao" readonly @isset($itens) value="{{ $itens->first()->itemItens->descricao ?? null }}" @endisset>
                                        </div>
                                        <div>
                                            <label>Coleção</label>
                                            <input type="text" class="form-control input-sm" id="colecao" readonly @isset($itens) value="{{ $itens->first()->itemItens->colitem ?? null }}" @endisset>
                                        </div>
                                        <div>
                                            <label>Grife</label>
                                            <input type="text" class="form-control input-sm" id="grife" readonly @isset($itens) value="{{ $itens->first()->itemItens->grife ?? null }}" @endisset>
                                        </div>
                                        <div>
                                            <label>RX / SL</label>
                                            <input type="text" class="form-control input-sm" id="tipo" readonly @isset($itens) value="{{ $itens->first()->itemItens->linha ?? null }}" @endisset>
                                        </div>
                                        @if(isset($itens->first()->itemItens->tecnologia) && !in_array($itens->first()->itemItens->tecnologia, ['', '.']))
                                            <div>
                                                <label>Tecnologia</label>
                                                <input type="text" class="form-control input-sm" id="tipo" readonly @isset($itens) value="{{ $itens->first()->itemItens->tecnologia }}" @endisset>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div style="width: 100%; height: 250px; border: 1px solid lightgray;">
                                        @if(isset($itens) && $itens->count() > 0)
                                            <img id="foto" src="https://gestao.goeyewear.com.br/teste999.php?referencia={{ $itens->first()->itemItens->secundario ?? null }}" class="img-responsive" style="width: 100%; height: 100%;">
                                        @endif
                                    </div>
                                </div>
                                    
                            </div>

                        </div>

                        <div class="box-footer">
                            <div>
                                @isset($caixa)<a href="/logistica/alocacoes/relatorio/{{$caixa->id}}" class="btn btn-flat btn-default btn-lg"><i class="fa fa-print"></i>Relatório</a>@endisset
                                <button type="submit" id="inserir-item" class="btn btn-primary btn-flat btn-lg">Inserir</button>
                            </div>
                        </div>

                    </div>

                </form>
            @endisset
        </div>

        <div class="col-md-12">
            @isset($caixa)
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-cube"></i> Itens </h3>
                        <span class="pull-right">
                            <a href="" id="modalDevolverTudoCaixaAlocacao" class="text-red">Devolver Tudo</a>
                            <form method="POST" action="{{ route('alocacoes.excluir-tudo', ['id' => $caixa->id]) }}" style="display: inline;"
                                onsubmit="return confirm('Tem certeza de que deseja excluir todos os itens desta caixa?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn-link text-red" style="margin-left: 10px;">Excluir Tudo</button>
                            </form>
                        </span>

                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-5">
                                <form method="GET" action="{{ url()->current() }}">
                                    <label for="referencia_pesquisa">Referência</label>
                                    <div style="display: flex;">
                                        <input type="text" id="referencia_pesquisa" name="referencia_pesquisa" class="form-control" @if(request()->has('referencia_pesquisa')) value="{{ request()->referencia_pesquisa}}" @endif>
                                        <button class="btn btn-primary" style="margin-left: 5px;">Pesquisar</button>
                                    </div>
                                </form>
                            </div>
                            @isset($caixa->alocacoes)
                                <div class="col-md-7">
                                    <span style="margin-left: 5px;" class="pull-right">{{ $caixa->alocacoes->count() }} referências</span>
                                    <br>
                                    <span style="margin-left: 5px;" class="pull-right">{{ $caixa->alocacoes->sum('qtde') }} peças</span>
                                </div>
                            @endisset
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="text-bold">ID</th>
                                        <th class="text-bold">Data & Hora</th>
                                        <th class="text-bold">Item</th>
                                        <th class="text-bold">Qtd</th>
                                        <th class="text-bold">Endereço</th>
                                        <th class="text-bold">Coleção</th>
                                        <th class="text-bold">Devolver ou Excluir</th>
                                        <th class="text-bold">Conferir</th>
                                    </tr>

                                    <tr>
                                        @forelse ($itens as $i => $item)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ (new Carbon\Carbon($item->created_at))->format('d/m/Y H:i:s') }}</td>
                                                <td>{{ $item->produto }}</td>
                                                <td>{{ $item->qtde }}</td>
                                                <td>{{ $caixa->locacao }}</td>
                                                <td>{{ $item->itemItens->colitem }}</td>
                                                <td>
                                                    <div>
                                                        <a class="btn btn-sm btn-default saidaItemAlocado" data-value="{{ $item->produto }}" data-caixa="{{ $caixa->id }}">Devolver</a>
                                                        <form method="POST" action="{{ route('alocacoes.destroy', ['id' => $item->id])}}" style="display: contents;">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button class="btn btn-sm btn-danger">Excluir</button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" max="{{ $item->qtde }}" class="form-control input-sm qtdeConferida" value="{{ $item->qtde_conferida }}" data-id-alocacao="{{ $item->id }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="8" align="center">

                                                Nenhum item na caixa

                                            </td>
                                        @endforelse
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset
        </div>

        <div class="col-md-12">
            @isset($caixa)
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-refresh"></i> Movimentações </h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered tabela3 dataTable no-footer" id="myTable" role="grid"
                            aria-describedby="myTable_info">
                            <thead>
                                <tr>
                                    <td width="15%" class="text-bold">Data/Hora</td>
                                    <td width="5%" class="text-bold">Operação</td>
                                    <td width="40%" class="text-bold">Item</td>
                                    <td width="10%" class="text-bold">Qtde</td>
                                    <td width="30%" class="text-bold">Usuário</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movimentacoes as $item)
                                    <tr>
                                        <td align="center">{{(new Carbon\Carbon($item->created_at))->format('d/m/Y H:i:s')}}</td>
                                        <td align="center">{{$item->operacao}}</td>
                                        <td>{{ ($item->produto->secundario ?? null) }}</td>
                                        <td align="center">{{$item->qtde}}</td>
                                        <td align="center">
                                        @if (! isset($item->usuario->nome) && $item->operacao == 'S')
                                            @if ($item->addressbook)
                                            {{ $item->addressbook->razao }}
                                            @endif
                                        @else
                                            {{ $item->usuario->nome }}
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            @endisset
        </div>
    </div>


@include('logistica.alocacoes.modals.modal_devolver')
@include('logistica.alocacoes.modals.modal_nova')
@include('logistica.alocacoes.modals.modal_editar')

<form class="form-horizontal" @isset($caixa) action="/logistica/alocacoes/{{ $caixa->id }}/esvaziar" @endisset method="get">

    <div class="modal fade" id="modalEsvaziarCaixaAlocacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Devolver Tudo</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-md-2 control-label">Código</label>
                        <div class="col-md-9">
                            <input type="text" name="codigo" class="form-control input-lg" placeholder="Funcionário"
                                required="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Devolver</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $("#modalDevolverTudoCaixaAlocacao").click(function(event) {
      /* Act on the event */
      event.preventDefault();
      $("#modalEsvaziarCaixaAlocacao").modal('show');
    
    });
    
</script>

@stop

@section('js')

<script src="/js/bootstrap-multiselect.js"></script>

<script>

    $(document).ready(function() {

        $('#grifes').multiselect({
            maxHeight: 200,
            enableFiltering: true,
            includeSelectAllOption: true,
            selectAllText: 'Selecionar todas',
            allSelectedText: 'Todas selecionadas',
            nonSelectedText: 'Nenhuma selecionada',
            nSelectedText: 'selecionadas',
        });

        $('.limitar').on('change', function(e){
            if($('#limite').attr('disabled') == 'disabled')
                $('#limite').removeAttr('disabled')
            else
                $('#limite').attr('disabled', 'disabled')
        })

        $('.qtdeConferida').on('keyup', function(e){

            let data = {
                qtde_conferida: $(e.target).val(),
            }

            fetch('/api/logistica/alocacoes/item/' + $(e.target).attr('data-id-alocacao') + '/conferir', {
                method: "POST",
                body: JSON.stringify(data),
                headers: {"Content-type": "application/json; charset=UTF-8"}
            })
            .then(response => {
                if(response.status != 200){
                    response.json().then(json => $(e.target).val(json.ultimoValor))
                }
                return response
            })
            .catch(err => { 
                console.log(err)
            });
        })
        
    });
</script>

@stop

@section('css')

<link rel="stylesheet" href="/css/bootstrap-multiselect.css"> 

<style>
    .TriSea-technologies-Switch > input[type="checkbox"] {
    display: none;   
}

.TriSea-technologies-Switch > label {
    cursor: pointer;
    height: 0px;
    position: relative; 
    width: 40px;  
}

.TriSea-technologies-Switch > label::before {
    background: rgb(0, 0, 0);
    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    content: '';
    height: 16px;
    margin-top: -8px;
    position:absolute;
    opacity: 0.3;
    transition: all 0.4s ease-in-out;
    width: 40px;
}
.TriSea-technologies-Switch > label::after {
    background: rgb(255, 255, 255);
    border-radius: 16px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    content: '';
    height: 24px;
    left: -4px;
    margin-top: -8px;
    position: absolute;
    top: -4px;
    transition: all 0.3s ease-in-out;
    width: 24px;
}
.TriSea-technologies-Switch > input[type="checkbox"]:checked + label::before {
    background: inherit;
    opacity: 0.5;
}
.TriSea-technologies-Switch > input[type="checkbox"]:checked + label::after {
    background: inherit;
    left: 20px;
}
.labels > div > label {
    margin-bottom: 2px !important;
    margin-top: 10px !important;
}

.box-header.with-border h3.box-title {
    font-size: 22px;
}

.box-header.with-border h3.box-title span {
    font-size: 21px;
}

.form-group {
    margin-top: 12px;
}

.form-group label.col-md-2.control-label span.pull-left {
    margin-top: 3px;
    font-size: 17px;
    font-weight: 600;
    color: red;
}


</style>
@stop
