@extends('layouts.app')

@section('template_title')
    Review
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Review') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('reviews.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Rating</th>
										<th>Author Id</th>
										<th>Work Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $review->title }}</td>
											<td>{{ $review->message }}</td>
											<td>{{ $review->rating }}</td>
											<td>{{ $review->author_id }}</td>
											<td>{{ $review->work_id }}</td>

                                            <td>
                                                <form action="{{ route('reviews.destroy',$review->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('reviews.show',$review->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('reviews.edit',$review->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $reviews->links() !!}
            </div>
        </div>
    </div>
@endsection
