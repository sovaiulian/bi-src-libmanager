<?php
    //Show books
    if($sortby==''){$sortby="id asc";}

    $tmp=$this->html->readRQcsv('ids');
    if ($tmp!=''){$sql.=" and id in ($tmp)";}

    $tmp=$this->html->readRQn('list_id');
    if ($tmp>0){$sql.=" and list_id=$tmp";}

    $sql1="select *";
    $sql=" from $what a where id>0 $sql";
    $sqltotal=$sql;
    $sql = "$sql order by $sortby";
    $sql2=" limit $limit offset $offset;";
    $sql=$sql1.$sql.$sql2;
    //$out.= $sql;


    echo $this->html->pre_display($sql,"SQL");
    $fields=array('id','name','date','isbn','link',);
    //$sort= $fields;
    $out=$this->html->tablehead($what,$qry, $order, 'no_addbutton', $fields,$sort);

    if (!($cur = pg_query($sql))) {$this->html->HT_Error( pg_last_error()."<br><b>".$sql."</b>" );}
    $rows=pg_num_rows($cur);if($rows>0)$csv.=$this->data->csv($sql);
    echo $this->html->pre_display($csv,"CSV");
    while ($row = pg_fetch_array($cur)) {
        $i++;
        $class='';
        //$type=$this->data->get_name('listitems',$row[type]);
        if($row[id]==0)$class='d';
        $out.= "<tr class='$class'>";
        $out.= $this->html->edit_rec($what,$row[id],'ved',$i);
        $out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
        $out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$row[name]</td>";
        //$shortDate = $date
        $out.= "<td>$row[date]</td>";
        $out.= "<td>$row[isbn]</td>";
        $out.= "<td>$row[link]</td>";
        $out.= "<td class='n'>".$this->html->money($row[amount])."</td>";
        $out.= "</tr>";
        $totals[2]+=$row[qty];
        if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];
        $this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));
    }
    $this->livestatus('');
    include(FW_DIR.'/helpers/end_table.php');
    