(function ($) {
  $(document).ready(function () {
    // Handle color swatch clicks
    $(".svsw_color-swatch").on("click", function () {
      var value = $(this).data("value");
      var select = $(this).closest(".value").find("select");
      select.val(value).change();
      $(".svsw_color-swatch").removeClass("selected");
      $(this).addClass("selected");
    });

    // Handle label swatch clicks
    $(".svsw_label-swatch").on("click", function () {
      var value = $(this).data("value");
      var select = $(this).closest(".value").find("select");
      select.val(value).change();
      $(".svsw_label-swatch").removeClass("selected");
      $(this).addClass("selected");
    });
  });
})(jQuery);
