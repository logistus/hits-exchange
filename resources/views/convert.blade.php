<x-layout title="{{ $page }}">
  <h4><a href="{{ url('convert') }}">Conversions</a></h4>
  <div class="alert alert-info mt-3">You have
    <strong>{{ Auth::user()->credits }}</strong> credits,
    <strong>{{ Auth::user()->banner_imps }}</strong> banner impressions,
    <strong>{{ Auth::user()->square_banner_imps }}</strong> square banner impressions,
    <strong>{{ Auth::user()->text_imps }}</strong> text ad impressions.
  </div>
  <div class="alert alert-secondary">Your 1 credit equals to <strong>{{ Auth::user()->type->credits_to_banner }}</strong> banner impsressions or <strong>{{ Auth::user()->type->credits_to_square_banner }}</strong> square banner impsressions or <strong>{{ Auth::user()->type->credits_to_text }}</strong> text impressions.</div>
  <x-alert />
  <form action="{{ url('convert') }}" method="POST" class="row row-cols-lg-auto g-5 align-items-center">
    @csrf
    <div class="col-12">Convert </div>
    <div class="col-12">
      <input type="text" id="convert-amount" name="convert_amount" class="form-control" value="{{ old('convert_amount') }}" style="width: 7rem;" />
    </div>
    <div class="col-12">
      <select class="form-select" id="convert-from" name="convert_from">
        <option value="credits" {{ old('convert_from') == "credits" ? "selected" : "" }}>Credits</option>
        <option value="banner_imps" {{ old('convert_from') == "banner_imps" ? "selected" : "" }}>Banner Impressions</option>
        <option value="square_banner_imps" {{ old('convert_from') == "square_banner_imps" ? "selected" : "" }}>Square Banner Impressions</option>
        <option value="text_imps" {{ old('convert_from') == "text_imps" ? "selected" : "" }}>Text Impressions</option>
      </select>
    </div>
    <div class="col-12">to</div>
    <div class="col-12">
      <input type="text" id="convert-result" name="convert_result" class="form-control" value="{{ old('convert_result') }}" style="width: 7rem;" readonly aria-readonly="true" />
    </div>
    <div class="col-12">
      <select class="form-select" id="convert-to" name="convert_to">
        <option value="banner_imps" {{ old('convert_to') == "banner_imps" ? "selected" : "" }}>Banner Impressions</option>
        <option value="square_banner_imps" {{ old('convert_to') == "square_banner_imps" ? "selected" : "" }}>Square Banner Impressions</option>
        <option value="text_imps" {{ old('convert_to') == "text_imps" ? "selected" : "" }}>Text Impressions</option>
      </select>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Convert</button>
    </div>
  </form>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      var user_credits = "{{ Auth::user()->credits }}" * 1;
      var user_banner_imps = "{{ Auth::user()->banner_imps }}" * 1;
      var user_text_imps = "{{ Auth::user()->text_imps }}" * 1;
      var credits_to_banner = "{{ Auth::user()->type->credits_to_banner }}" * 1;
      var credits_to_square_banner = "{{ Auth::user()->type->credits_to_square_banner }}" * 1;
      var credits_to_text = "{{ Auth::user()->type->credits_to_text }}" * 1;
      var convert_from = $("#convert-from");
      var convert_to = $("#convert-to");
      var convert_amount = $("#convert-amount");
      var convert_result = $("#convert-result");
      var credits = new Option("Credits", "credits");
      var banner_imps = new Option("Banner impressions", "banner_imps");
      var square_banner_imps = new Option("Square Banner impressions", "square_banner_imps");
      var text_imps = new Option("Text Impressions", "text_imps");

      function show_conversions() {
        if (convert_from.val() == "credits" && convert_to.val() == "banner_imps") {
          convert_result.val(convert_amount.val() * credits_to_banner);
        }
        if (convert_from.val() == "credits" && convert_to.val() == "square_banner_imps") {
          convert_result.val(convert_amount.val() * credits_to_square_banner);
        }
        if (convert_from.val() == "credits" && convert_to.val() == "text_imps") {
          convert_result.val(convert_amount.val() * credits_to_text);
        }
        if (convert_from.val() == "banner_imps" && convert_to.val() == "text_imps") {
          convert_result.val(Math.round((convert_amount.val() * (credits_to_text / credits_to_banner))));
        }
        if (convert_from.val() == "square_banner_imps" && convert_to.val() == "text_imps") {
          convert_result.val(Math.round((convert_amount.val() * (credits_to_text / credits_to_square_banner))));
        }
        if (convert_from.val() == "text_imps" && convert_to.val() == "banner_imps") {
          convert_result.val(Math.round((convert_amount.val() / (credits_to_text / credits_to_banner))));
        }
      }

      function update_options() {
        convert_to.empty();
        if (convert_from.val() == "credits") {
          convert_to.append(banner_imps);
          convert_to.append(square_banner_imps);
          convert_to.append(text_imps);
          convert_to.val("banner_imps");
          convert_result.val(convert_amount.val() * credits_to_banner);
        } else if (convert_from.val() == "banner_imps") {
          convert_to.append(text_imps);
          convert_to.val("text_imps");
          convert_result.val(Math.round((convert_amount.val() * (credits_to_text / credits_to_banner))));
        } else if (convert_from.val() == "square_banner_imps") {
          convert_to.append(text_imps);
          convert_to.val("text_imps");
          convert_result.val(Math.round((convert_amount.val() * (credits_to_text / credits_to_square_banner))));
        } else {
          convert_to.append(banner_imps);
          convert_to.append(square_banner_imps);
          convert_to.val("banner_imps");
          convert_result.val(Math.round((convert_amount.val() / (credits_to_text / credits_to_banner))));
        }
      }

      update_options();

      convert_from.change(function(e) {
        update_options();
      });

      convert_to.change(function() {
        show_conversions();
      });

      convert_amount.keyup(function() {
        show_conversions();
      });
    });

  </script>
</x-layout>
