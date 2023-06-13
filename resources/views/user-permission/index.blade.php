@extends('layouts.app')

@section('template_title')
    User Permission
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('User Permission') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('user-permissions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Is Can Create Recruit</th>
										<th>Is Can Create Work</th>
										<th>Is Can Review Work</th>
										<th>Is Can Reply Review</th>
										<th>Is Can Access Supervisor</th>
										<th>Is Can Access Admin</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userPermissions as $userPermission)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $userPermission->is_can_create_recruit }}</td>
											<td>{{ $userPermission->is_can_create_work }}</td>
											<td>{{ $userPermission->is_can_review_work }}</td>
											<td>{{ $userPermission->is_can_reply_review }}</td>
											<td>{{ $userPermission->is_can_access_supervisor }}</td>
											<td>{{ $userPermission->is_can_access_admin }}</td>

                                            <td>
                                                <form action="{{ route('user-permissions.destroy',$userPermission->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('user-permissions.show',$userPermission->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('user-permissions.edit',$userPermission->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $userPermissions->links() !!}
            </div>
        </div>
    </div>
@endsection
