@extends('layouts.app')

@section('template_title')
    Work Booking
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Work Booking') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('work-bookings.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Customer Message</th>
										<th>Mobile Phone</th>
										<th>Latitude</th>
										<th>Longitude</th>
										<th>Booking Status</th>
										<th>Customer Confirm Status</th>
										<th>Worker Confirm Status</th>
										<th>Author Id</th>
										<th>Work Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workBookings as $workBooking)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $workBooking->customer_message }}</td>
											<td>{{ $workBooking->mobile_phone }}</td>
											<td>{{ $workBooking->latitude }}</td>
											<td>{{ $workBooking->longitude }}</td>
											<td>{{ $workBooking->booking_status }}</td>
											<td>{{ $workBooking->customer_confirm_status }}</td>
											<td>{{ $workBooking->worker_confirm_status }}</td>
											<td>{{ $workBooking->author_id }}</td>
											<td>{{ $workBooking->work_id }}</td>

                                            <td>
                                                <form action="{{ route('work-bookings.destroy',$workBooking->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('work-bookings.show',$workBooking->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('work-bookings.edit',$workBooking->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $workBookings->links() !!}
            </div>
        </div>
    </div>
@endsection
