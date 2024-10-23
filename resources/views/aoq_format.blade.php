			<table>
				<tr>
					<td class="font-bold pr-1" width="8%">PR No: </td>
					<td class="">{{ $pr_no }}</td>
					<td class=" font-bold pr-1" width="8%">AOQ No: </td>
					<td class="">{{ $aoq_no }}</td>
					<td class=" font-bold pr-1" width="8%">Requested By: </td>
					<td class="">{{ $requestor }}</td>
				</tr>
				<tr>
					<td class="font-bold pr-1">Department: </td>
					<td class="">{{ $department }}</td>
					<td class=" font-bold pr-1">Date: </td>
					<td class="">{{ $aoq_date }}</td>
					<td class=" font-bold pr-1">Date Needed: </td>
					<td class="">{{ $date_needed }}</td>
				</tr>
				<tr>
					<td class="font-bold pr-1">End-Use:</td>
					<td class="">{{ $enduse }}</td>
				</tr>
				<tr>
					<td class="font-bold pr-1">Purpose:</td>
					<td class="">{{ $purpose }}</td>
				</tr>
			</table>
                    <br>
                    <br>
					<table style="border: 1px solid black; border-collapse: collapse;" width="250%">
						<tr>
							<td class="bg-gray-50 " colspan="4"></td>
							<!-- loop vendors here start -->
                            @foreach($aoq_vendor_data AS $av)
								<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
								<p class="m-0 text-xs font-bold">{{ $av['vendor_name']; }}</p><br>
								<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
								<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
								<div class="flex justify-center space-x-2">
									<span>{{ $av['contact_person']; }}</span>
									<span>|</span>
									<span>{{ $av['phone']; }}</span>
								</div>
							    </td>
							@endforeach
							<!-- loop vendors here end-->
						</tr>
                        <tr>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="1%">#</td>
							<td class="uppercase bg-gray-100 p-1 align-top" width="10%">Item Description</td>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">Qty</td>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">UOM</td>
							<!-- loop offer header per vendor here start -->
                            @foreach($aoq_vendor_data AS $av)
							<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
							<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
							<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
							<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
                            @endforeach
							<!-- loop offer header per vendor here end-->
						</tr>
                        @php
                            $itemno = 1;
                        @endphp
                        @foreach($aoq_items_data AS $ai)
							<tr>
								<td class="p-1 align-top text-center"  rowspan="3">{{ $itemno }}</td>
								<td class="p-1 align-top" rowspan="3">{{ $ai['item_description']; }}</td>
								<td class="p-1 align-top text-center" rowspan="3">{{  number_format($ai['quantity'],2) }}</td>
								<td class="p-1 align-top text-center" rowspan="3">{{  $ai['uom']; }}</td>
								@foreach($first_offers AS $fo)
								@if($av['rfq_vendor_id']==$fo['rfq_vendor_id'])
								<td>{{$fo['offer'];}}</td>
								@if($ai['min_price']==$fo['unit_price'])
								<td style='background-color: #FDE047'>{{ $fo['currency'] }} {{ $fo['unit_price'] }}</td>
								@else
								<td>{{ $fo['currency'] }} {{ $fo['unit_price'] }}</td>
								@endif
								@if($fo['awarded'] == 1)
								<td style='background-color: #84CC16'>{{ $fo['currency'] }}  {{ $fo['unit_price'] * $ai['quantity'] }}</td>
								@else
								<td>{{ $fo['currency']}}  {{$fo['unit_price'] * $ai['quantity']}}</td>
								@endif
								<td>{{$fo['remarks'];}}</td>
								@endif
								@endforeach
                            </tr>
							<tr>
							@foreach($second_offers AS $so)
								@if($av['rfq_vendor_id']==$so['rfq_vendor_id'])
								<td>{{$so['offer'];}}</td>
								@if($ai['min_price']==$so['unit_price'])
								<td style='background-color: #FDE047'>{{ $so['currency'] }} {{ $so['unit_price'] }}</td>
								@else
								<td>{{ $so['currency'] }} {{ $so['unit_price'] }}</td>
								@endif
								@if($so['awarded'] == 1)
								<td style='background-color: #84CC16'>{{ $so['currency'] }}  {{ $so['unit_price'] * $ai['quantity'] }}</td>
								@else
								<td>{{ $so['currency']}}  {{$so['unit_price'] * $ai['quantity']}}</td>
								@endif
								<td>{{$so['remarks'];}}</td>
								@endif
								@endforeach
							</tr>
							<tr>
							@foreach($third_offers AS $to)
								@if($av['rfq_vendor_id']==$to['rfq_vendor_id'])
								<td>{{$to['offer'];}}</td>
								@if($ai['min_price']==$to['unit_price'])
								<td style='background-color: #FDE047'>{{ $to['currency'] }} {{ $to['unit_price'] }}</td>
								@else
								<td>{{ $to['currency'] }} {{ $to['unit_price'] }}</td>
								@endif
								@if($to['awarded'] == 1)
								<td style='background-color: #84CC16'>{{ $to['currency'] }}  {{ $to['unit_price'] * $ai['quantity'] }}</td>
								@else
								<td>{{ $to['currency']}}  {{$to['unit_price'] * $ai['quantity']}}</td>
								@endif
								<td>{{$to['remarks'];}}</td>
								@endif
								@endforeach
							</tr>
                            @php
                                $itemno++;
                            @endphp
                            @endforeach
							<tr>
							<td colspan="4" class="p-0 !border-0">
								<table class="w-full">
									<tr>
										<td class="!border-0 text-right px-2" colspan="">Legend:</td>
										<td class="!border-0" colspan="2"></td>
										<td class="!border-0" width="50%"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
										<td class="!border-0 bg-lime-500" width="20%"></td>
										<td class="!border-0"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
										<td class="!border-0 bg-yellow-300"></td>
										<td class="!border-0"></td>
									</tr>
								</table>
							</td>
							@foreach($aoq_vendor_data AS $av)
							<td colspan="5" class="p-0 !border-0">
								<table class="w-full">
									<tr>
										<td class="!border-0 text-start font-bold">Terms and Conditions</td>
									</tr>
									@php
										$termno = 1;
									@endphp
									@foreach($all_terms AS $at)
										@if($av['rfq_vendor_id']==$at['rfq_vendor_id'])
										<td class="!border-0 text-start" >{{ $termno }} {{ $at['terms'] }}</td>
										@endif
									@endforeach
									</tr>
									@php
										$termno++;
									@endphp
								</table>
							</td>
							@endforeach
						</tr>
						<tr>
							<td><br></td>
						</tr>
					</table>
                    <br>
                    <br>
                    <br>
                    <table class="!text-xs" width="150%">
						<tr>
							<td></td>
							<td width="12%">Prepared by:</td>
							<td></td>
							<td width="12%">Received and Checked by</td>
							<td></td>
							<td width="12%">Award Recommended by</td>
							<td></td>
							<td width="12%">Recommending Approval</td>
							<td></td>
							<td width="12%">Approved by</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td class=""><br></td>
							<td></td>
							<td class=""></td>
							<td></td>
							<td class=""></td>
							<td></td>
							<td class=""></td>
							<td></td>
							<td class=""></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td class="border-b">
								{{ $prepared_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ $received_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ $award_recommended_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ $recommended_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ $approved_by_name }}
							</td>
							<td></td>
						</tr>
					</table>
					
