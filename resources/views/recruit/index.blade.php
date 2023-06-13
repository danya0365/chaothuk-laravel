@extends('layouts.app')

@section('template_title')
    Recruit
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Recruit') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('recruits.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Description</th>
										<th>Primary Image</th>
										<th>Images</th>
										<th>Budget</th>
										<th>Display Priority</th>
										<th>Recruit Status</th>
										<th>Province Id</th>
										<th>Work Type Id</th>
										<th>Author Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recruits as $recruit)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $recruit->title }}</td>
											<td>{{ $recruit->description }}</td>
											<td>{{ $recruit->primary_image }}</td>
											<td>{{ $recruit->images }}</td>
											<td>{{ $recruit->budget }}</td>
											<td>{{ $recruit->display_priority }}</td>
											<td>{{ $recruit->recruit_status }}</td>
											<td>{{ $recruit->province_id }}</td>
											<td>{{ $recruit->work_type_id }}</td>
											<td>{{ $recruit->author_id }}</td>

                                            <td>
                                                <form action="{{ route('recruits.destroy',$recruit->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('recruits.show',$recruit->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('recruits.edit',$recruit->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $recruits->links() !!}
            </div>
        </div>
    </div>
@endsection
