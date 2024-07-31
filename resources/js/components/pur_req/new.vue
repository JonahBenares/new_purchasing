<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{HomeIcon, UserIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
</script>
<template>
	<navigation>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<span class="pt-2">
						<h3 class="card-title !text-lg m-0">Purchase Request <small>New</small></h3>
					</span>
					<div class="pt-3">
						<hr class="border-dashed mt-0">
						<div class="row">							
							<div class="col-lg-6 offset-lg-3 col-md-3">
								<div class="form-group">
								<label class="text-gray-500 m-0" for="">Import PR Excel File here</label>
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
									<input type="text" class="form-control" placeholder="Purchase Request">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">PR No</label>
									<input type="text" class="form-control" placeholder="PR No">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">New PR No</label>
									<input type="text" class="form-control" placeholder="New PR No">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Date Prepared</label>
									<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Prepared">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Department</label>
									<input type="text" class="form-control" placeholder="Department">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Urgency</label>
									<input type="text" class="form-control" placeholder="New PR No">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Process Code</label>
									<input type="text" class="form-control" placeholder="New PR No">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">End-Use</label>
									<input type="text" class="form-control" placeholder="End-Use">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Purpose</label>
									<input type="text" class="form-control" placeholder="Purpose">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered">
									<tr>
										<td>#</td>
										<td>Qty</td>
										<td>UOM</td>
										<td>PN No.</td>
										<td>Item Description</td>
										<td>WH Stocks</td>
										<td>Date Needed</td>
										<td>add button</td>
									</tr>
									<tr></tr>
								</table>
							</div>
						</div>
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <button class="btn btn-light">Cancel</button> -->
									<button type="submit" class="btn btn-primary mr-2 w-44">Submit</button>
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