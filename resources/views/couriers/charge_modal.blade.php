<div id="chargeModal" class="modal-block mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Courier Charge Process</h2>
        </header>
        <div class="panel-body">


            <div class="form-group" >
                <label class="col-sm-3 control-label">Amount</label>
                <div class="col-sm-9">
                    <input type="text" name="text" v-model="selectedCourier.amount" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Weight</label>
                <div class="col-sm-9">

                    <div class="input-group mb-md">
                        <input type="text" name="weight" v-model="selectedCourier.weight" class="form-control" />
                        <span class="input-group-addon ">Kg/Grm</span>
                    </div>
                </div>
            </div>



            <div class="form-group" v-if="selectedCourier.is_pickup =='pickup'">
                <label class="col-sm-3 control-label">Pickup Charge</label>
                <div class="col-sm-9">
                    <input type="text" name="pickup_charge" class="form-control" v-model="selectedCourier.pickup_charge"   />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Total</label>
                <div class="col-sm-9">
                    @{{totalCharge}}
                </div>
            </div>

        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary" @click="savePayment">Save</button>
                    <button class="btn btn-default modal-dismiss">Cancel</button>
                </div>
            </div>
        </footer>
    </section>
</div>
