<?php
    function messagesToHTML($messages){
        $res = "";
        $data = new DataLayer();
        foreach($messages as $value){
            $res.= "<li date='{$value['datetime']}'><p><span>From : </span>{$value['pseudo']}</p><p>{$value['content']}</p></li>";
        }
        return $res;
    }

    function usersToOptionsHTML($users){
        $res="";
        foreach($users as $user){
            $res.="<option value='{$user['userId']}' onclick='action_findMessages'>{$user['pseudo']}</option>";
        }
        return $res;
    }

    function subsToHTML($subs){
        $res="";
        foreach($subs as $sub){
            $res.= "<li id='{$sub['userId']}'><p>{$sub['pseudo']} vous suit</p></li>";
        }
        return $res;
    }

    function followersToHTML($followers){
        $res="";
        foreach($followers as $follower){
            if ($value['mutual'])
                $res.= "<li id='{$follower['userId']}'><p>{$follower['pseudo']} vous suit</p></li>";
            else
                $res.= "<li id='{$follower['userId']}'><p>{$follower['pseudo']}</p></li>";
        }
        return $res;
    }
?>