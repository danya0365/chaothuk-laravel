@extends('layouts.app')

@section('template_title')
    Reply
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Reply') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('replies.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Title</th>
										<th>Message</th>
										<th>Review Id</th>
										<th>Author Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($replies as $reply)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $reply->title }}</td>
											<td>{{ $reply->message }}</td>
											<td>{{ $reply->review_id }}</td>
											<td>{{ $reply->author_id }}</td>

                                            <td>
                                                <form action="{{ route('replies.destroy',$reply->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('replies.show',$reply->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('replies.edit',$reply->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $replies->links() !!}
            </div>
        </div>
    </div>
@endsection
