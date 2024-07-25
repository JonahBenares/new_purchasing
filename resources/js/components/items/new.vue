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
						<h3 class="card-title !text-lg m-0">Item<small class="px-1">New</small></h3>
					</span>
					<div class="pt-3">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Item Name</label>
									<textarea class="form-control" placeholder="Item Name"></textarea>	
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Specification</label>
									<textarea class="form-control" placeholder="Specification"></textarea>	
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Brand</label>
									<input type="text" class="form-control" placeholder="Brand">
								</div>
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Part Number</label>
									<input type="text" class="form-control" placeholder="Part Number">
								</div>
							</div>
						</div>
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