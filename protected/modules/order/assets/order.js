$(document).ready(function(){

  Number.prototype.toFixed = function(x){
    return Math.round(this * Math.pow(10, x)) / Math.pow(10, x);
  }

  // -- ajax / request
  function BasketAction(action, id, hash, value, attributes, callback)
  {
    var action = action || 'list',
      value = value || 0,
      hash = hash || null,
      attributes = attributes || new Array,
      id = id || null,
      callback = callback  || procBasket,
      data = {'action': action};

    if (id){
      data.id = id;
      data.value = value;
      data.attributes = attributes;
    }else if (!!hash){
      data.hash = hash;
      data.value = value;
      data.attributes = attributes;
    }

    $.ajax({
      url: url_basket,
      data: data,
      dataType: 'json',
      success: callback
    });
  }

  /* basket actions: add */
  $(".add-basket").click(function(){
    var id_product = $(this).attr('id_product'),
        value = parseInt($('#product-'+id_product).val());
        attributes = {};

    if (id_product){
      var size = new Array();
      $('.product-sizes[id_product="' + id_product + '"] .attribute-size.selected').each(function(index) {
        size.push($(this).attr('id_size'));
      });

      if (size.length > 0)
        attributes.size = size;

      BasketAction('add', id_product, null, value, attributes);
    }
    return false;
  });

  // удаление продукции
  $('.basket-list .delete').click(function(){
    var product_hash = $(this).attr('product_hash');
    if (!!product_hash){
      BasketAction('delete', null, product_hash);
      $("#basket-product-"+product_hash).animate({'opacity': 0}, 700, function(){$(this).remove();});
    }
    return false;
  });

  // добавление единицы продукции
  $('.basket-list .plus').click(function(e){
    var product_hash = $(this).attr('product_hash');
    if (product_hash)
      BasketAction('add', null, product_hash, 1);
    return false;
   });

  // удаление единицы продукции
  $('.basket-list .minus').click(function(e) {
    var product_hash = $(this).attr('product_hash');
    if (product_hash)
      BasketAction('reduce', null, product_hash, 1);
    return false;
  });

  // изменение единиц продукции
  $('.basket-list input.value').change(function(){
    var product_hash = $(this).attr('product_hash'),
      value = parseInt($(this).val());

    if (value == '')
      return;

    if (product_hash)
      BasketAction('set', null, product_hash, value);
  });

  // добавление значения
  $('.product-order .plus').click(function(e){
    var id_product = $(this).attr('id_product');
    if (id_product){
        var count = parseInt($('#product-'+id_product).attr('value'));
        $('#product-'+id_product).attr('value', count+1);
    }
    return false;
   });

  // удаление значения
  $('.product-order .minus').click(function(e){
    var id_product = $(this).attr('id_product');
    if (id_product){
        var count = parseInt($('#product-'+id_product).attr('value')) - 1;
        if (count > 0)
            $('#product-'+id_product).attr('value', count);
        else
            $('#product-'+id_product).attr('value', 0);
    }
    return false;
   });


  // добавление единицы продукции
  $('.product-order input[type="submit"]').click(function(e){
    var id_product = $(this).attr('id_product'),
        attributes = {};
    if (id_product){
      var count = parseInt($('#product-'+id_product).attr('value'));
      if (count > 0){

        var size = new Array();
        $('.product-sizes[id_product="' + id_product + '"] .attribute-size.selected').each(function(index) {
          size.push($(this).attr('id_size'));
        });

        $('.product-sizes[id_product="' + id_product + '"] .attribute-size.selected').removeClass('selected');
        if (size.length > 0)
          attributes.size = size;

        BasketAction('add', id_product, null, count, attributes);
        $('#product-'+id_product).attr('value', 0);

      }
    }
    return false;
   });


  // UI:
  function procBasket(result)
  {
    data_basket = result.data;

    for (index in data_basket.product)
      procProduct(index);

    $("#order_total").html(result.data.total.count);
    $("#order_total_count").html(result.data.total.count);
    $("#order_total_cost").html(result.data.total.cost);

  }

  function procProduct(product_hash)
  {
    var product = data_basket.product[product_hash];

    $('#product-' + product_hash).attr('value', product.count);
    $('#product-cost-' + product_hash).html(product.cost);
  }


  $('.product-sizes .attribute-size').click(function(){
    if ($(this).hasClass('selected'))
      $(this).removeClass('selected');
    else
      $(this).addClass('selected');
  });

  // Анимация
  $(".add-basket").click(function(){
    if ($(this).hasClass('active'))
        return false;
    var id_product = $(this).attr('id_product');
    if (id_product)
      $('.product-sizes[id_product="' + id_product + '"] .attribute-size.selected').removeClass('selected');

    icon = $(this).clone();
    icon.css("position", "absolute");
    icon.css("opacity", "1");
    icon.css("z-index", "999");
    icon.css("left", $(this).offset().left);
    icon.css("top",  $(this).offset().top + 1 - parseInt($(this).css('margin-top')));
    $('body').append(icon);
    icon.animate({
       'left': $(this).offset().left+50,
       'opacity': 0
    }, 350 , function(){
       icon.remove();
    });
    return false;
  });

  $('.product-order input[type="submit"]').click(function(){
    icon = $(this).clone();
    icon.css("position", "absolute");
    icon.css("z-index", "999");
    icon.css("left", $(this).offset().left);
    icon.css("top",  $(this).offset().top + 1 - parseInt($(this).css('margin-top')));
    $('body').append(icon);
    icon.animate({
       'left': $(this).offset().left+50,
       'opacity': 0
    }, 350 , function(){
       icon.remove();
    });
    return false;
  });

});