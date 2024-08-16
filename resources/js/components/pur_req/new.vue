<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

	let item_list=ref([]);
	let item_no=ref();
	let qty=ref('');
	let uom=ref('');
	let pn_no=ref('');
	let item_desc=ref('');
	let wh_stocks=ref('');
	let date_needed=ref('');

	const addItem= () => {
		if(qty.value == ''){
			alert("Quantity must not be empty!")
		}else if(uom.value == ''){
			alert("Uom must not be empty!")
		}else if(item_desc.value == ''){
			alert("Item Description must not be empty!")
		}else if(date_needed.value == ''){
			alert("Date Needed must not be empty!")
		}else{
		const items = {
			item_no:item_no.value,
			qty:qty.value,
			uom:uom.value,
			pn_no:pn_no.value,
			item_desc:item_desc.value,
			wh_stocks:item_desc.value,
			date_needed:date_needed.value,
		}
		item_list.value.push(items)
		qty.value='';
		uom.value='';
		pn_no.value='';
		item_desc.value='';
		wh_stocks.value='';
		date_needed.value='';
		}
	}

	const removeItem = (index) => {
		item_list.value.splice(index,1)
	}

	const removeItem1 = () => {
		const item1 = document.getElementById("item1");
		item1.remove();
	}

	const removeItem2 = () => {
		const item1 = document.getElementById("item2");
		item1.remove();
	}

	const removeItem3 = () => {
		const item1 = document.getElementById("item3");
		item1.remove();
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Request <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_req">Purchase Request</a></li>
                            <li class="breadcrumb-item active" aria-current="page">New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<hr class="border-dashed mt-0">
					<div class="pt-1">
						<div class="row">							
							<div class="col-lg-6 offset-lg-3 col-md-3">
								<div class="form-group">
								<label class="text-gray-500 m-0" for="">Import PR Excel File here or Manual encode below</label>
								<input type="file" name="img[]" class="file-upload-default">
								<div class="input-group col-xs-12">
									<input type="file" class="form-control file-upload-info" placeholder="Upload Image">
									<span class="input-group-append">
										<button class="btn btn-primary" type="button">Upload</button>
									</span>
								</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div class="row">
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Purchase Request</label>
									<input type="text" class="form-control" placeholder="Purchase Request" value="Bacolod">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">PR No</label>
									<input type="text" class="form-control" placeholder="PR No" value="PR-BCD24-1209">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">New PR No</label>
									<input type="text" class="form-control" placeholder="New PR No" value="PR-CENPRI24-1002">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Date Prepared</label>
									<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Prepared" value="01/16/24">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Department</label>
									<input type="text" class="form-control" placeholder="Department" value="IT Department">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Urgency</label>
									<input type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Process Code</label>
									<input type="text" class="form-control" placeholder="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">End-Use</label>
									<input type="text" class="form-control" placeholder="End-Use" value="IT Department">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Purpose</label>
									<input type="text" class="form-control" placeholder="Purpose" value="Replace damage monitor, mouse and keyboard">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="5%">#</td>
										<td class="p-1 uppercase text-center" width="7%">Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="10%">WH Stocks</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
										<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr>
										<td class=""><input placeholder="#" type="text" v-model="item_no" class="w-full p-1 text-center" disabled></td>
										<td class=""><input placeholder="Qty" type="text" v-model="qty" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" class="w-full p-1 text-center"></td>
										<td class=""><input placeholder="UOM" type="text" v-model="uom" class="w-full p-1 text-center"></td>
										<td class=""><input placeholder="PN No." type="text" v-model="pn_no" class="w-full p-1"></td>
										<td class=""><input placeholder="Item Description" v-model="item_desc" type="text" class="w-full p-1"></td>
										<td class=""><input placeholder="WH Stock" type="text" v-model="wh_stocks" class="w-full p-1"></td>
										<td class=""><input placeholder="Date Needed" type="text" v-model="date_needed" class="w-full p-1" onfocus="(this.type='date')"></td>
										<td class="text-center">
											<button class="btn btn-primary p-1" @click="addItem">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr id="item1">
										<td class="p-1 text-center">1</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-001</td>
										<td class="p-1">Monitor</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="text-center">
											<button class="btn btn-danger p-1" @click="removeItem1()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr id="item2">
										<td class="p-1 text-center">2</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-222</td>
										<td class="p-1">Mouse</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="text-center">
											<button class="btn btn-danger p-1" @click="removeItem2(2)">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr id="item3">
										<td class="p-1 text-center">3</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-333</td>
										<td class="p-1">Keyboard</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="text-center">
											<button class="btn btn-danger p-1" @click="removeItem3(3)">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr v-for="(i,index) in item_list">
										<td class="p-1 text-center">{{ index + 4 }}</td>
										<td class="p-1 text-center">{{ i.qty }}</td>
										<td class="p-1 text-center">{{ i.uom }}</td>
										<td class="p-1">{{ i.pn_no }}</td>
										<td class="p-1">{{ i.item_desc }}</td>
										<td class="p-1">{{ i.wh_stocks }}</td>
										<td class="p-1">{{ i.date_needed }}</td>
										<td class="text-center">
											<button class="btn btn-danger p-1" @click="removeItem(index)">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <button class="btn btn-light">Cancel</button> -->
									<button type="submit" class="btn btn-danger mr-2 w-36">Cancel</button>
									<button type="submit" class="btn btn-warning text-white mr-2 w-36">Save as Draft</button>
									<a href="/pur_quote/new" type="submit" class="btn btn-primary mr-2 w-44">Save and Proceed</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</navigation>
</template>