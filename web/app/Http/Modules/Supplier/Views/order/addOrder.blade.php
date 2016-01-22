<html>
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    {{--<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>--}}
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
</head>
<body>
<div>



    <span>Balance   : &nbsp;&nbsp;&nbsp;${{Session::get('ig_supplier')['account_bal']}} </span><br><br>
    <form action="/supplier/addOrder" method="post" id="addOrderForm">

        <div>
            <h4>Add New Order(s)</h4><br>
            Choose Service <br>
            <select name="plan_id" id="plan_id">
                <option value="">Please Select a service</option>
                @if(isset($data))
                    <optgroup label='Intagram'>
                        @foreach($data as $plan)
                            <option value="{{$plan['plan_id']}}"
                                    data-minQuantity="{{$plan['min_quantity']}}"
                                    data-maxQuantity="{{$plan['max_quantity']}}"
                                    data-chargePerUnit="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>
                        @endforeach
                    </optgroup>
                @endif
            </select>&nbsp;&nbsp;<span id="plan_id_error"></span><br>

            Order Link <br>
            <input type="url" name="order_url" id="order_url" placeholder="Your Video,Image,Page,Profile URL">
            <span id="order_url_error"></span><br>

            Amount to Delivery <br>
            <input type="number" name="quantity" id="quantity"
                   placeholder="Amount of Likes, Followers you want in that Link">&nbsp;<span
                    id="quantity_error"></span>
            <br>
            <input type="submit" name="submit_order" id="submit_order" value="Place Order">
            &nbsp;&nbsp;&nbsp;
            <button name="reset_order" id="reset">Reset</button>
            &nbsp;&nbsp;&nbsp;

        </div>
    </form>

</div>

<div>
    <div>
        <h3>ORDER RESUME</h3><br>
        Order Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="order_total">-</span><br><br>
        Price per Unit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="price">-</span><br>
        Delivery time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="delivery_time" -></span><br>
        Delivery for 1K&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="delivery_for1K">-</span><br>
        Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="status">-</span><br>
        Min.Order&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="min_order">-</span><br>
        Max.p/Link&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="max_order">-</span><br>
        Current Balance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="current_balance">${{Session::get('ig_supplier')['account_bal']}} </span><br>
        Pricing & Info &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Click here</a><br>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#plan_id').change(function (e) {
            e.preventDefault();
            $('#order_total').text('0');
            $('#price').text($('#plan_id option:selected').attr('data-chargePerUnit'));
            $('#delivery_for1K').text('0');
            $('#status').text('0');
            $('#min_order').text($('#plan_id option:selected').attr('data-minQuantity'));
            $('#max_order').text($('#plan_id option:selected').attr('data-maxQuantity'));
        });


        $('#quantity').change(function (e) {
            e.preventDefault();
            var quantity = $('#quantity').val();
            $('#order_total').text($('#plan_id option:selected').attr('data-chargePerUnit') * quantity);
        });



        $('#addOrderForm').validate({
            rules: {
                plan_id: {required: true},
                order_url: {required: true, url: true},
                quantity: {required: true}
            },
            messages: {
                plan_id: {
                    required: "Please Select a Service"
                },
                order: {
                    required: "Please type Correct URL"
                },
                quantity: {
                    required: "Please enter amount to delivery"
                }
            }
        });

        $('#reset').click(function (e) {
            e.preventDefault();
            $('#plan_id option:selected').removeAttr('selected');
            $('#order_url').val('');
            $('#quantity').val('');
            $('#order_total').text('-');
            $('#price').text('-');
            $('#delivery_for1K').text('-');
            $('#status').text('-');
            $('#min_order').text('-');
            $('#max_order').text('-');

        });


    });
</script>
</body>
</html>