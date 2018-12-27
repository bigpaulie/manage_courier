<div id="recipientModal" class="modal-block mfp-hide" style="max-width: 800px">
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Recipient Details</h2>
        </header>
        <div class="panel-body">

            <table class="table table-no-more table-bordered table-striped mb-none">

                <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Address1</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Phone</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(courier, index) in repicipentAddress" >
                    <td><input type="radio" @click="selectRecipient(courier)"  class="" name="courier_id" ></td>
                    <td>@{{courier.r_name}}</td>
                    <td>@{{courier.r_company}}</td>
                    <td>@{{courier.r_address1}}</td>
                    <td>@{{ courier.receiver_country.name }}</td>
                    <td>@{{courier.r_state}}</td>
                    <td>@{{courier.r_city}}</td>
                    <td>@{{courier.r_phone}}</td>

                </tr>
                </tbody>
            </table>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary" @click="fillRecipient()">Ok</button>
                    <button class="btn btn-default modal-dismiss" @click="cancelFillRecipent()">Cancel</button>
                </div>
            </div>
        </footer>
    </section>
</div>
