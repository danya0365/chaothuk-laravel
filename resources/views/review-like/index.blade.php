@extends('layouts.app')

@section('template_title')
    Review Like
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Review Like') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('review-likes.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Review Id</th>
										<th>Author Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviewLikes as $reviewLike)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $reviewLike->review_id }}</td>
											<td>{{ $reviewLike->author_id }}</td>

                                            <td>
                                                <form action="{{ route('review-likes.destroy',$reviewLike->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('review-likes.show',$reviewLike->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('review-likes.edit',$reviewLike->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $reviewLikes->links() !!}
            </div>
        </div>
    </div>
@endsection
