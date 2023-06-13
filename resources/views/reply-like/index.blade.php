@extends('layouts.app')

@section('template_title')
    Reply Like
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Reply Like') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('reply-likes.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Reply Id</th>
										<th>Author Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($replyLikes as $replyLike)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $replyLike->reply_id }}</td>
											<td>{{ $replyLike->author_id }}</td>

                                            <td>
                                                <form action="{{ route('reply-likes.destroy',$replyLike->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('reply-likes.show',$replyLike->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('reply-likes.edit',$replyLike->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $replyLikes->links() !!}
            </div>
        </div>
    </div>
@endsection
