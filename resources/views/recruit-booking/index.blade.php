@extends('layouts.app')

@section('template_title')
    Recruit Booking
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Recruit Booking') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('recruit-bookings.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Recruit Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recruitBookings as $recruitBooking)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $recruitBooking->customer_message }}</td>
											<td>{{ $recruitBooking->mobile_phone }}</td>
											<td>{{ $recruitBooking->latitude }}</td>
											<td>{{ $recruitBooking->longitude }}</td>
											<td>{{ $recruitBooking->booking_status }}</td>
											<td>{{ $recruitBooking->customer_confirm_status }}</td>
											<td>{{ $recruitBooking->worker_confirm_status }}</td>
											<td>{{ $recruitBooking->author_id }}</td>
											<td>{{ $recruitBooking->recruit_id }}</td>

                                            <td>
                                                <form action="{{ route('recruit-bookings.destroy',$recruitBooking->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('recruit-bookings.show',$recruitBooking->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('recruit-bookings.edit',$recruitBooking->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $recruitBookings->links() !!}
            </div>
        </div>
    </div>
@endsection
