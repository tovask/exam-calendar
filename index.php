<?php
require "before_content.php";
?>
                <table class="table text-center week-table">
                    <thead>
                        <tr>
<?php
$today = get_today_first_time();
for($i=0;$i<7;$i++){
    print "                                <th>".strftime('%A',$today + $i*24*60*60)."</th>\n"; //date('l',$today + $i*24*60*60)
}
?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<?php
for($i=0;$i<7;$i++){
    print "                                <td>";
    $day_events = execquery("SELECT * FROM `events` WHERE time>='".mysql_date($today + $i*24*60*60)."' AND time<'".mysql_date($today + ($i+1)*24*60*60)."' ORDER BY `time` ");
    if(count($day_events) < 1){
        print "<span>Nincs ezen a napon esemény</span>";
    }else{  // [ ['title'=>'test','time'=>$today], ['title'=>'test','time'=>$today] ]
        foreach($day_events as $event){
            print "<a href='event.php?id=".$event['id']."' class='mystyle-".$event['style_id']."' ><span>".$event['title']."</span><br>".date('H:i',strtotime($event['time']))."</a><br>";
        }
    }
    print "</td>\n";
}
?>
                        </tr>
                    </tbody>
                </table>
<?php
require "after_content.php";
?>