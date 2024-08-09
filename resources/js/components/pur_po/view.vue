<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();

	const showAddVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
	const openAddVendor = () => {
		showAddVendor.value = !showAddVendor.value
	}
	const openPreview = () => {
		showPreview.value = !showPreview.value
	}
	const closeModal = () => {
		showAddVendor.value = !hideModal.value
		showPreview.value = !hideModal.value
	}

</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Order <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_po">Purchase Order</a></li>
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
                            <div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
                                        <span class="text-sm text-gray-700">PO-CENPRI24-1001</span>
                                    </div>
                                    <div class="col-lg-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
                                        <span class="text-sm text-gray-700">05/16/24</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
                                        <span class="text-sm text-gray-700">IT Department</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Address:</span>
                                        <span class="text-sm text-gray-700">February 16, 2024</span>
                                    </div>
                                    <div class="col-lg-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Telephone: </span>
                                        <span class="text-sm text-gray-700">(034) 9927 9272</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Contact Person: </span>
                                        <span class="text-sm text-gray-700">rfq_it001</span>
                                    </div>
                                    <div class="col-lg-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Telefax: </span>
                                        <span class="text-sm text-gray-700">(034 0882 97263)</span>
                                    </div>
                                </div>
                                
                                <div class="" >
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="border-2">
                                                <!-- <table class="table-bordered w-full !text-sm">
                                                    <tr>
                                                        <td colspan="4" class="py-2 bg-gray-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="px-2 " width="10%">PR Number:</td>
                                                        <td class="px-2" width="40%">PR-8626-2772</td>
                                                        <td class="px-2 " width="10%">Requestor:</td>
                                                        <td class="px-2" width="40%"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="px-2">Purpose:</td>
                                                        <td class="px-2"></td>
                                                        <td class="px-2">End-use:</td>
                                                        <td class="px-2"></td>
                                                    </tr>
                                                </table> -->
                                                <table class="table-bordered w-full !text-xs">
                                                    <tr class="bg-gray-100">
                                                        <td class="uppercase p-1 text-center" width="3%">#</td>
                                                        <td class="uppercase p-1 text-center" width="7%">Qty</td>
                                                        <td class="uppercase p-1 text-center" width="7%">Unit</td>
                                                        <td class="uppercase p-1" colspan="2">Description</td>
                                                        <td class="uppercase p-1 text-center" width="12%">Unit Price</td>
                                                        <td class="uppercase p-1 text-center" width="12%">Total</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-y-none p-1 text-center">1</td>
                                                        <td class="border-y-none p-1 text-center">3</td>
                                                        <td class="border-y-none p-1 text-center">pc</td>
                                                        <td class="border-y-none p-1" colspan="2">Monitor</td>
                                                        <td class="border-y-none p-1 text-right">200.00</td>
                                                        <td class="border-y-none p-1 text-right">200.00</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-y-none p-1 text-center">1</td>
                                                        <td class="border-y-none p-1 text-center">3</td>
                                                        <td class="border-y-none p-1 text-center">pc</td>
                                                        <td class="border-y-none p-1" colspan="2">Mouse</td>
                                                        <td class="border-y-none p-1 text-right">200.00</td>
                                                        <td class="border-y-none p-1 text-right">200.00</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-y-none p-1 text-center">1</td>
                                                        <td class="border-y-none p-1 text-center">3</td>
                                                        <td class="border-y-none p-1 text-center">pc</td>
                                                        <td class="border-y-none p-1" colspan="2">Keyboard</td>
                                                        <td class="border-y-none p-1 text-right">200.00</td>
                                                        <td class="border-y-none p-1 text-right">200.00</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class=""></td>
                                                        <td class=""></td>
                                                        <td class=""></td>
                                                        <td class=""></td>
                                                        <td class=""></td>
                                                        <td class=""></td>
                                                        <td class=""></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="5">
                                                            <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>PR-19772-8727</p>
                                                            <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
                                                            <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>DG2 Injector Cooling Inlet Pipe Header Common Flange(Flexible Hose)</p>
                                                            <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>Materials for Modification of Boiler Shell for Replacement of Smokestack Pedestal Shell and Dismantling and Installation of Horizontal Exhaust Main Header Spool</p>
                                                        </td>
                                                        <td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
                                                        <td class="p-1 text-right ">9927.00</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
                                                        <td class="p-1 text-right ">9927.00</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled value="999">
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled value="999.00">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
                                                        <td class="p-1 text-right ">9927.00</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
                                                        <td class="p-1 text-right font-bold !text-sm">2000.00</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <table class="table-bordsered !text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">1.</td>
                                                    <td class="align-top px-1" colspan="2">PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">2.</td>
                                                    <td class="align-top px-1" colspan="2">Sub-standard items shall be returned to supplier @ no cost to CENPRI.</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">3.</td>
                                                    <td class="align-top pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span >Price is </span>
                                                            <span>Exclusive of VAT</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">4.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>Payment </span>
                                                            <span> COD</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">5.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>Delivery Time </span>
                                                            <span> Sample</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">6.</td>
                                                    <td class="align-top  pl-1" colspan="2">sample term</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-lg-6">
                                            <table class="table-bordsered !text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Other Instructions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">1.</td>
                                                    <td class="px-1" colspan="2">sample instructions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">2.</td>
                                                    <td class="px-1" colspan="2">sample instructions -Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <hr	class="border-dashed">
                                    <div class="row my-2"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-between space-x-2">
                                                <div class="flex justify-between space-x-1">
                                                    <button type="submit" class="btn btn-info w-26">Revise PO</button>
                                                </div>
                                                <div class="flex justify-between space-x-1">
                                                    <button type="submit" class="btn btn-warning !text-white w-26">Print RFD</button>
                                                    <a href="/pur_dr/new" class="btn btn-warning !text-white w-26">Print DR</a>
                                                    <button type="submit" class="btn btn-primary w-36" >Print PO</button>
                                                </div>
                                                
                                            </div>
                                        </div>
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