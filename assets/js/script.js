(function ($) {
  function sendAjax(
    admin_ajax,
    action,
    params,
    successFun,
    beforeFun,
    parent_block = null
  ) {
    return new Promise(function (resolve, reject) {
      $.ajax({
        url: `${admin_ajax}?action=${action}`,
        data: params,
        type: "POST",
        dataType: "json",
        beforeSend: (xhr) => {
          parent_block.find(".ap-loader").show();
          beforeFun();
        },
        success: function (data) {
          successFun(data);
          resolve("success");
        },
        error: function (err) {
          console.log(err);
          reject("error");
        },
      });
    });
  }

  $(".ap-more-btn").on("click", async function () {
    const admin_url = $(this).attr("data-admin-ajax");
    const query = $(this).attr("data-query");
    const settings = $(this).attr("data-settings");
    const total_posts = parseInt($(this).attr("data-total-posts"));
    const offset =
      parseInt(
        $(this).parents(".arifix-ap-wrapper").find(".ap-post-single").length
      ) + parseInt($(this).attr("data-query-offset"));
    const _wpnonce = $(this).attr("data-wp-nonce");

    const parent_block = $(this).parents(".arifix-ap-wrapper");

    sendAjax(
      admin_url,
      "get_awesome-posts",
      { query, settings, offset, _wpnonce },
      function (data) {
        parent_block.find(".ap-post-single").last().after(data.result);
        parent_block.find(".ap-loader").hide();

        const current_post_count = parseInt(
          parent_block.find(".ap-post-single").length
        );

        if (total_posts <= current_post_count) {
          parent_block.find(".ap-more-btn").hide();
        }

        parent_block.find(".ap-more-btn .spinner").remove();
      },
      function () {
        parent_block.find(".ap-loader").show();
        parent_block
          .find(".ap-more-btn")
          .prepend(`<span class="spinner"></span>`);
      },
      parent_block
    );
  });
})(jQuery);
