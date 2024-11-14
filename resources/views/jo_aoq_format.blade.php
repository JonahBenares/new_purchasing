<table>
	<tr>
		<td style="width: 25px"></td>
		<td style="width: 250px"></td>
		<td style="width: 35px"></td>
		<td style="width: 40px"></td>
		<td style="width: 220px"></td>
		<td style="width: 100px"></td>
		<td style="width: 100px"></td>
		<td style="width: 100px"></td>
		<td style="width: 220px"></td>
		<td style="width: 100px"></td>
		<td style="width: 100px"></td>
		<td style="width: 100px"></td>
		<td style="width: 220px"></td>
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
		<td colspan="{{ $colspan }}" style="height: 30px;text-align:center;vertical-align:middle">
			<b>ABSTRACT OF QUOTATION</b>
		</td>
	</tr>
	<tr>
		<td colspan="5">JOR No: {{ $jor_no }}</td>
		<td colspan="3">AOQ No: {{ $aoq_no }} </td>
		<td colspan="2" class="">Requested By: {{ $requestor }}</td>
	</tr>
	<tr>
		<td colspan="5" class="font-bold pr-1">Department: {{ $department }}</td>
		<td colspan="3" class="">Date: {{ $aoq_date }}</td>
		<td colspan="2" class="">Date Needed: {{ $date_needed }}</td>
	</tr>
	<tr>
		<td  colspan="10" class="font-bold pr-1">Purpose: {{ $purpose }}</td>
	</tr>
	<tr>
		<td  colspan="10" class="font-bold pr-1">Project Title: {{ $project_activity }}</td>
	</tr>
</table>
<table style="border: 1px solid black; border-collapse: collapse;">
	<tr>
		<td class="" colspan="4" style="border-bottom:1px solid white"></td>
		@foreach($aoq_vendor_data AS $av)
			<td class="" colspan="4" style="text-align: center;vertical-align: text-top;border-right:1px solid gray;border-left:1px solid gray; border-top:1px solid gray;  border-bottom:1px solid white">
				<b>{{ $av['vendor_name']; }}</b>
			</td>
		@endforeach
	</tr>
	<tr>
		<td class="" colspan="4" style=""></td>
		@foreach($aoq_vendor_data AS $av)
			<td class="" colspan="4" style="text-align: center;vertical-align: text-top;border-right:1px solid gray;border-left:1px solid gray">
				<div style="">
					<span>{{ $av['contact_person']; }}</span>
					<span>|</span>
					<span>{{ $av['phone']; }}</span>
				</div>
			</td>
		@endforeach
	</tr>
	<tr>
		<td style="background:#f2f2f2;border:1px solid gray;text-align:center">#</td>
		<td style="background:#f2f2f2;border:1px solid gray;">Description</td>
		<td style="background:#f2f2f2;border:1px solid gray;text-align:center">Qty</td>
		<td style="background:#f2f2f2;border:1px solid gray;text-align:center">UOM</td>
		@foreach($aoq_vendor_data AS $av)
		<td style="background:#f2f2f2;border:1px solid gray;">Offer</td>
		<td style="background:#f2f2f2;border:1px solid gray;text-align:center" >Unit Price</td>
		<td style="background:#f2f2f2;border:1px solid gray;text-align:center">Amount</td>
		<td style="background:#f2f2f2;border:1px solid gray;">Comment</td>
		@endforeach
	</tr>
	<tr>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align: left;">{{ $general_description }}</td>
	</tr>

	@php
		$laborno = 1;
	@endphp
	@foreach($aoq_labor_data AS $ald)
	<tr>
	<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align: left;text-align:center" rowspan="3">{{ $laborno }}</td>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap" rowspan="3">{{ $ald['scope_of_work']; }}</td>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align:center" rowspan="3">{{  number_format($ald['quantity'],2) }}</td>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align:center" rowspan="3">{{  $ald['uom']; }}</td>

		@foreach($first_labor_offers AS $flo)
			@if($flo['jor_labor_details_id']==$ald['jor_labor_details_id'])
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$flo['offer'];}}</td>
			@if($ald['min_price']==$flo['unit_price'] && $status != 'Cancelled')
				<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $flo['currency'] }} {{ number_format($flo['unit_price'],2) }}</td>
			@else
				<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $flo['currency'] }} {{ number_format($flo['unit_price'],2) }}</td>
			@endif
			@if($flo['awarded'] == 1 && $status != 'Cancelled')
				<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $flo['currency'] }}  {{ number_format($flo['unit_price'] * $ald['quantity'],2) }}</td>
			@else
				<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $flo['currency']}}  {{ number_format($flo['unit_price'] * $ald['quantity'],2) }}</td>
			@endif
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$flo['comments'];}}</td>
			@endif
		@endforeach
	</tr>
	<tr>
		@foreach($second_labor_offers AS $slo)
			@if($slo['jor_labor_details_id']==$ald['jor_labor_details_id'])
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$slo['offer'];}}</td>
			@if($ald['min_price']==$slo['unit_price'] && $status != 'Cancelled')
				<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $slo['currency'] }} {{ number_format($slo['unit_price'],2) }}</td>
			@else
				<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $slo['currency'] }} {{ number_format($slo['unit_price'],2) }}</td>
			@endif
			@if($slo['awarded'] == 1 && $status != 'Cancelled')
				<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $slo['currency'] }}  {{ number_format($slo['unit_price'] * $ald['quantity'],2) }}</td>
			@else
				<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $slo['currency']}}  {{ number_format($slo['unit_price'] * $ald['quantity'],2) }}</td>
			@endif
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$slo['comments'];}}</td>
			@endif
		@endforeach
	</tr>
	<tr>
		@foreach($third_labor_offers AS $tlo)
			@if($tlo['jor_labor_details_id']==$ald['jor_labor_details_id'])
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$tlo['offer'];}}</td>
			@if($ald['min_price']==$tlo['unit_price'] && $status != 'Cancelled')
				<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $tlo['currency'] }} {{ number_format($tlo['unit_price'],2) }}</td>
			@else
				<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $tlo['currency'] }} {{ number_format($tlo['unit_price'],2) }}</td>
			@endif
			@if($tlo['awarded'] == 1 && $status != 'Cancelled')
				<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $tlo['currency'] }}  {{ number_format($tlo['unit_price'] * $ald['quantity'],2) }}</td>
			@else
				<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $tlo['currency']}}  {{ number_format($tlo['unit_price'] * $ald['quantity'],2) }}</td>
			@endif
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$tlo['comments'];}}</td>
			@endif
		@endforeach
	</tr>
	@php
		$laborno++;
	@endphp
	@endforeach
	
	<tr>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align: left;" colspan="3">Materials</td>
	</tr>
	@php
		$materialno = 1;
	@endphp
	@foreach($aoq_material_data AS $amd)
	<tr>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align: left;text-align:center" rowspan="3">{{ $materialno }}</td>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap" rowspan="3">{{ $amd['item_description']; }}</td>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align:center" rowspan="3">{{  number_format($amd['quantity'],2) }}</td>
		<td style="border: 1px solid gray;vertical-align: text-top;text-wrap: wrap; text-align:center" rowspan="3">{{  $amd['uom']; }}</td>
		@foreach($first_offers AS $fo)
		@if($amd['jor_material_details_id']==$fo['jor_material_details_id'])
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$fo['offer'];}}</td>
		@if($amd['min_price']==$fo['unit_price'] && $status != 'Cancelled')
			<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $fo['currency'] }} {{ number_format($fo['unit_price'],2) }}</td>
		@else
			<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $fo['currency'] }} {{ number_format($fo['unit_price'],2) }}</td>
		@endif
		@if($fo['awarded'] == 1 && $status != 'Cancelled')
			<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $fo['currency'] }}  {{ number_format($fo['unit_price'] * $amd['quantity'],2) }}</td>
		@else
			<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $fo['currency']}}  {{ number_format($fo['unit_price'] * $amd['quantity'],2) }}</td>
		@endif
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$fo['remarks'];}}</td>
		@endif
		@endforeach
	</tr>
	<tr>
	@foreach($second_offers AS $so)
		@if($amd['jor_material_details_id']==$so['jor_material_details_id'])
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$so['offer'];}}</td>
		@if($amd['min_price']==$so['unit_price'] && $status != 'Cancelled')
			<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $so['currency'] }} {{ number_format($so['unit_price'],2) }}</td>
		@else
			<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $so['currency'] }} {{ number_format($so['unit_price'],2) }}</td>
		@endif
		@if($so['awarded'] == 1 && $status != 'Cancelled')
			<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $so['currency'] }}  {{ number_format($so['unit_price'] * $amd['quantity'],2) }}</td>
		@else
			<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $so['currency']}}  {{ number_format($so['unit_price'] * $amd['quantity'],2) }}</td>
		@endif
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$so['remarks'];}}</td>
		@endif
		@endforeach
	</tr>
	<tr>
	@foreach($third_offers AS $to)
		@if($amd['jor_material_details_id']==$to['jor_material_details_id'])
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$to['offer'];}}</td>
		@if($amd['min_price']==$to['unit_price'] && $status != 'Cancelled')
			<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #FDE047'>{{ $to['currency'] }} {{ number_format($to['unit_price'],2) }}</td>
		@else
			<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $to['currency'] }} {{ number_format($to['unit_price'],2) }}</td>
		@endif
		@if($to['awarded'] == 1 && $status != 'Cancelled')
			<td style='border: 1px solid gray;vertical-align: text-top;text-align:center;background-color: #84CC16'>{{ $to['currency'] }}  {{ number_format($to['unit_price'] * $amd['quantity'],2) }}</td>
		@else
			<td style="border: 1px solid gray;vertical-align: text-top;text-align:center;">{{ $to['currency']}}  {{ number_format($to['unit_price'] * $amd['quantity'],2) }}</td>
		@endif
			<td style="border: 1px solid gray;vertical-align: text-top;word-wrap: break-word;">{{$to['remarks'];}}</td>
		@endif
		@endforeach
	</tr>
	@php
		$materialno++;
	@endphp
	@endforeach
	
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align:right">Legend:</td>
		@foreach($aoq_vendor_data AS $av)
		<td></td>
		<td></td>
		<td style="text-align:right">Lowest Price</td>
		<td style="text-align:center;background-color: #FDE047" ></td>
		@endforeach
		<!-- <td></td>
		<td></td>
		<td style="text-align:right">Lowest Price</td>
		<td style="text-align:center;background-color: #FDE047" ></td>
		<td></td>
		<td></td>
		<td style="text-align:right">Lowest Price</td>
		<td style="text-align:center;background-color: #FDE047" ></td> -->
	</tr>
	<tr>
		<td></td>
		<td style="text-align:right"></td>
		@foreach($aoq_vendor_data AS $av)
		<td></td>
		<td></td>
		<td style="text-align:right">Recommended Award</td>
		<td style="text-align:center;background-color: #84CC16"></td>
		@endforeach
		<!-- <td></td>
		<td></td>
		<td style="text-align:right">Recommended Award</td>
		<td style="text-align:center;background-color: #84CC16"></td>
		<td></td>
		<td></td>
		<td style="text-align:right">Recommended Award</td>
		<td style="text-align:center;background-color: #84CC16"></td> -->
	</tr>
</table>
<table>
	<tr>
		<td></td>
		<td style="vertical-align:text-top; text-align:right; height: 150px;"> <p>Terms and Conditions:</p> </td>
		<td></td>
		<td></td>
		@foreach($aoq_vendor_data AS $av)
		<td colspan="4" style="vertical-align: text-top; height: 150px;">
				@php
					$letter = 'a'; // Starting letter
				@endphp		
				@foreach($all_terms AS $at)
					@if($av['jo_rfq_vendor_id']==$at['jo_rfq_vendor_id'])
						<p>{{ $letter }}. {{ $at['terms'] }}</p>
						@php
							$letter = chr(ord($letter) + 1); // Increment letter
						@endphp
					@endif
				@endforeach
			</td>
		
		@endforeach
	</tr>
	
</table>



<table class="!text-xs">
	<tr>
		<td></td>
		<td style="text-align:left">Prepared by:</td>
		<td></td>
		<td></td>
		<td style="text-align:center">Received and Checked by</td>
		<td style="text-align:center" colspan="2">Award Recommended by</td>
		<td></td>
		<td style="text-align:center">Recommending Approval</td>
		<td style="text-align:center" colspan="2">Approved by</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border-bottom:black"></td>
		<td></td>
		<td></td>
		<td style="border-bottom:black"></td>
		<td style="border-bottom:black" colspan="2"></td>
		<td></td>
		<td style="border-bottom:black"></td>
		<td style="border-bottom:black" colspan="2"></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align:left">{{ $prepared_by_name }}</td>
		<td></td>
		<td></td>
		<td style="text-align:center">{{ $received_by_name }}</td>
		<td style="text-align:center" colspan="2">{{ $award_recommended_by_name }}</td>
		<td></td>
		<td style="text-align:center">{{ $recommended_by_name }}</td>
		<td style="text-align:center" colspan="2">{{ $approved_by_name }}</td>
		<td></td>
	</tr>
</table>
				
