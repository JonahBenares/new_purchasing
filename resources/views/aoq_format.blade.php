
	<table>
		<tr>
			<td style="width: 25px"></td>
			<td style="width: 250px"></td>
			<td style="width: 35px"></td>
			<td style="width: 40px"></td>
			<td style="width: 200px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 200px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 200px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
		</tr>
	</table>
	<table style="border:2px solid #000">
		<tr>
			<td colspan="2" class="font-bold pr-1">PR No: </td>
			<td colspan="2" class="">{{ $pr_no }}</td>
			<td class=" font-bold pr-1">AOQ No: </td>
			<td colspan="2" class="">{{ $aoq_no }}</td>
			<td class=" font-bold pr-1">Requested By: </td>
			<td colspan="2" class="">{{ $requestor }}</td>
		</tr>
		<tr>
			<td colspan="2" class="font-bold pr-1">Department: </td>
			<td colspan="2" class="">{{ $department }}</td>
			<td class=" font-bold pr-1">Date: </td>
			<td colspan="2" class="">{{ $aoq_date }}</td>
			<td class=" font-bold pr-1">Date Needed: </td>
			<td colspan="2" class="">{{ $date_needed }}</td>
		</tr>
		<tr>
			<td  colspan="2" class="font-bold pr-1">End-Use:</td>
			<td  colspan="7" class="">{{ $enduse }}</td>
		</tr>
		<tr>
			<td  colspan="2" class="font-bold pr-1">Purpose:</td>
			<td  colspan="7" class="">{{ $purpose }}</td>
		</tr>
	</table>
	<table style="border: 1px solid black; border-collapse: collapse;">
		<tr>
			<td class="" colspan="4" style="border-bottom:0px"></td>
			@foreach($aoq_vendor_data AS $av)
				<td class="" colspan="4" style="text-align: center;vertical-align: text-top;border-bottom:0px">
					<b>{{ $av['vendor_name']; }}</b>
				</td>
			@endforeach
		</tr>
		<tr>
			<td class="" colspan="4" style=""></td>
			@foreach($aoq_vendor_data AS $av)
				<td class="" colspan="4" style="height: 30px;text-align: center;vertical-align: text-top">
					<div style="">
						<span>{{ $av['contact_person']; }}</span>
						<span>|</span>
						<span>{{ $av['phone']; }}</span>
					</div>
				</td>
			@endforeach
		</tr>
		<tr>
			<td style="background:#e0e0e0;border:1px solid #242424;">#</td>
			<td style="background:#e0e0e0;border:1px solid #242424;">Item Description</td>
			<td style="background:#e0e0e0;border:1px solid #242424;text-align:center">Qty</td>
			<td style="background:#e0e0e0;border:1px solid #242424;text-align:center">UOM</td>
			@foreach($aoq_vendor_data AS $av)
			<td style="background:#e0e0e0;border:1px solid #242424;">Offer</td>
			<td style="background:#e0e0e0;border:1px solid #242424;text-align:center" >Unit Price</td>
			<td style="background:#e0e0e0;border:1px solid #242424;text-align:center">Amount</td>
			<td style="background:#e0e0e0;border:1px solid #242424;">Comment</td>
			@endforeach
		</tr>
		@php
			$itemno = 1;
		@endphp
		@foreach($aoq_items_data AS $ai)
			<tr>
				<td style="vertical-align: text-top;text-wrap: wrap; text-align: left" rowspan="3">{{ $itemno }}</td>
				<td style="vertical-align: text-top;text-wrap: wrap" rowspan="3">{{ $ai['item_description']; }}</td>
				<td style="vertical-align: text-top;text-wrap: wrap; text-align:center" rowspan="3">{{  number_format($ai['quantity'],2) }}</td>
				<td style="vertical-align: text-top;text-wrap: wrap; text-align:center" rowspan="3">{{  $ai['uom']; }}</td>
				@foreach($first_offers AS $fo)
				@if($ai['pr_details_id']==$fo['pr_details_id'])
					<td style="vertical-align: text-top;word-wrap: break-word;">{{$fo['offer'];}}</td>
				@if($ai['min_price']==$fo['unit_price'])
					<td style='vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $fo['currency'] }} {{ $fo['unit_price'] }}</td>
				@else
					<td style="vertical-align: text-top;text-align:center;">{{ $fo['currency'] }} {{ $fo['unit_price'] }}</td>
				@endif
				@if($fo['awarded'] == 1)
					<td style='vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $fo['currency'] }}  {{ $fo['unit_price'] * $ai['quantity'] }}</td>
				@else
					<td style="vertical-align: text-top;text-align:center;">{{ $fo['currency']}}  {{$fo['unit_price'] * $ai['quantity']}}</td>
				@endif
					<td style="vertical-align: text-top;word-wrap: break-word;">{{$fo['remarks'];}}</td>
				@endif
				@endforeach
			</tr>
			<tr>
			@foreach($second_offers AS $so)
				@if($ai['pr_details_id']==$so['pr_details_id'])
					<td style="vertical-align: text-top;word-wrap: break-word;">{{$so['offer'];}} sadas asd asdasd asdadasdasd asd asdasd asdassasda sadasd</td>
				@if($ai['min_price']==$so['unit_price'])
					<td style='vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $so['currency'] }} {{ $so['unit_price'] }}</td>
				@else
					<td style="vertical-align: text-top;text-align:center;">{{ $so['currency'] }} {{ $so['unit_price'] }}</td>
				@endif
				@if($so['awarded'] == 1)
					<td style='vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $so['currency'] }}  {{ $so['unit_price'] * $ai['quantity'] }}</td>
				@else
					<td style="vertical-align: text-top;text-align:center;">{{ $so['currency']}}  {{$so['unit_price'] * $ai['quantity']}}</td>
				@endif
					<td style="vertical-align: text-top;word-wrap: break-word;">{{$so['remarks'];}}</td>
				@endif
				@endforeach
			</tr>
			<tr>
			@foreach($third_offers AS $to)
				@if($ai['pr_details_id']==$to['pr_details_id'])
					<td style="vertical-align: text-top;word-wrap: break-word;">{{$to['offer'];}}</td>
				@if($ai['min_price']==$to['unit_price'])
					<td style='vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $to['currency'] }} {{ $to['unit_price'] }}</td>
				@else
					<td style="vertical-align: text-top;text-align:center;">{{ $to['currency'] }} {{ $to['unit_price'] }}</td>
				@endif
				@if($to['awarded'] == 1)
					<td style='vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $to['currency'] }}  {{ $to['unit_price'] * $ai['quantity'] }}</td>
				@else
					<td style="vertical-align: text-top;text-align:center;">{{ $to['currency']}}  {{$to['unit_price'] * $ai['quantity']}}</td>
				@endif
					<td style="vertical-align: text-top;word-wrap: break-word;">{{$to['remarks'];}}</td>
				@endif
				@endforeach
			</tr>
			@php
				$itemno++;
			@endphp
			@endforeach
			<tr>
			{{-- <td colspan="4" class="p-0 !border-0">
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
			</td> --}}
			<!-- @foreach($aoq_vendor_data AS $av)
			<td colspan="5" class="p-0 !border-0">
				<table class="w-full">
					<tr>
						<td class="!border-0 text-start font-bold">Terms and Conditions</td>
					</tr>
					@foreach($all_terms AS $at)
						@if($av['rfq_vendor_id']==$at['rfq_vendor_id'])
						<td class="!border-0 text-start" >{{ $at['terms'] }}</td>
						@endif
					@endforeach
					</tr>
				</table>
			</td>
			@endforeach -->
		</tr>
		<tr>
			<td><br></td>
		</tr>
	</table>
	<table class="!text-xs">
		<tr>
			<td></td>
			<td>Prepared by:</td>
			<td></td>
			<td>Received and Checked by</td>
			<td></td>
			<td>Award Recommended by</td>
			<td></td>
			<td>Recommending Approval</td>
			<td></td>
			<td>Approved by</td>
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
					
