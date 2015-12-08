(function(){
    var URL_UPDATE_LIST = 'list/{permalink}/edit/{prop}';
    var $name;
    var name;
    var r_name = null;
    var t_name = null;

    function init()
    {
        $name = document.querySelector('input[name="list[name_list]"]');
        name = $name.value;

        $name.addEventListener('blur', nameBlurHandler, false);
    }

    function nameBlurHandler()
    {
        var newname = $name.value.trim();
        $name.value = newname;
        if(newname === name)
        {
            return;
        }

        var permalink = list.permalink;

        if(r_name !== null)
        {
            r_name.cancel();
        }
        if(t_name)
        {
            clearTimeout(t_name);
        }
        $name.classList.add("loading");
        r_name = new Request(URL_UPDATE_LIST.replace('{permalink}', permalink).replace('{prop}', 'name_list'), {value:newname}, 'POST');
        r_name.onComplete(function(e)
        {
            $name.classList.remove("loading");
            r_name = null;
            var c = "error";
            if(e.responseJSON)
            {
                if(e.responseJSON.error)
                {
                    console.log("nop");
                }
                else
                {
                    c = "confirmation";
                    name = newname;
                    document.querySelector("title").innerHTML = name+" - Savely.co";
                    document.querySelector('#lists_stick a[href="list/'+permalink+'/edit"]').innerHTML = name;
                    console.log("ok");
                }
            }

            $name.classList.add(c);

            t_name = setTimeout(function(){
                $name.classList.remove(c);
            }, 3000);
        });
    }

    window.addEventListener('load', init, false);
})();