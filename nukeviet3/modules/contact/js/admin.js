function nv_chang_status(a){nv_settimeout_disable("change_status_"+a,5E3);var b=document.getElementById("change_status_"+a).options[document.getElementById("change_status_"+a).selectedIndex].value;nv_ajax("post",script_name,nv_name_variable+"="+nv_module_name+"&"+nv_fc_variable+"=change_status&id="+a+"&new_status="+b+"&num="+nv_randomPassword(8),"","nv_chang_status_res")}function nv_chang_status_res(a){if(a!="OK"){alert(nv_is_change_act_confirm[2]);window.location.href=strHref}}
function nv_row_del(a){confirm(nv_is_del_confirm[0])&&nv_ajax("post",script_name,nv_name_variable+"="+nv_module_name+"&"+nv_fc_variable+"=del_row&id="+a,"","nv_row_del_result");return false}function nv_row_del_result(a){if(a=="OK")window.location.href=strHref;else alert(nv_is_del_confirm[2]);return false}
function nv_del_submit(a,b){var c=0;if(a[b].length)for(var d=0;d<a[b].length;d++){if(a[b][d].checked==true){c=1;break}}else if(a[b].checked==true)c=1;c&&confirm(nv_is_del_confirm[0])&&a.submit();return false}function nv_delall_submit(){if(confirm(nv_is_del_confirm[0]))window.location.href=script_name+"?"+nv_name_variable+"="+nv_module_name+"&"+nv_fc_variable+"=del&t=3";return false}
function nv_del_mess(a){if(confirm(nv_is_del_confirm[0]))window.location.href=script_name+"?"+nv_name_variable+"="+nv_module_name+"&"+nv_fc_variable+"=del&t=1&id="+a;return false};