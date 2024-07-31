<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, PencilIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="flex justify-between">
                            <span class="pt-2">
                                <h3 class="card-title !text-lg m-0">Request for Quotation <small>List</small></h3>
                            </span>
                            <span>
                                <div class="d-flex justify-content-between align-items-end flex-wrap space-x-2">
                                    <button type="button" class="btn btn-light !bg-gray-100 px-2 py-2 mt-2 mt-xl-0 !text-center !text-gray-500" title="export">
                                        <ArrowUpOnSquareIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ArrowUpOnSquareIcon>
                                    </button>
                                    <a href="/pur_quote/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                        <span>Add New RFQ</span>
                                    </a>
                                </div>
                            </span>
                        </div>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered table-hover !border ">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="12%"> RFQ Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> RFQ No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> PR No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">
                                        <td class="!text-xs"> ITB24-1322-CNPR </td>
                                        <td class="!text-xs"> 2024-07-30 </td>
                                        <td class="!text-xs"> 2024-07-16 </td>
                                        <td class="!text-xs"> Fuel and Lube Management </td>
                                        <td class="!text-xs"> 0 </td>
                                        <td class="!text-xs"> Fleur de Liz Ambong / Rey D. Argawanon </td>
                                        <td class="!text-xs p-1" align="center">
                                            <a href="/unit/edit" class="btn btn-xs btn-info text-white p-1">
                                                <PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PencilIcon>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</navigation>
</template>