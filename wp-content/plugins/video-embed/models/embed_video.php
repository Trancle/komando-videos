<?php

class VideoEmbed
{
    public $id = null;
    public $name, $video_id, $offset;

    function create($params = [])
    {
        foreach ($params as $key => $value) {
            $this->set($key, $value);
        }
       $status =  $this->save();
        return $status;
    }

    function set($key, $value)
    {
        $this->{$key} = $value;
    }

    function get($key)
    {

    }

    function save()
    {
        if (!isset($wpdb))
            $wpdb = $GLOBALS['wpdb'];

        $table_name = $wpdb->prefix . 'embed_video';
        $embed_url = 'http://www.youtube.com/embed/' . $this->video_id;

        if ($this->source == 'youtube') {
            $initial_image_url =  'http://img.youtube.com/vi/';
        }
        $image_url = $initial_image_url .$this->video_id.'/0.jpg';
        $this->{'embed_url'} = $embed_url;
        $this->{'still_image'} = $image_url;
        $status = $wpdb->insert(
            $table_name,
            array(
                'name' => $this->name,
                'video_id' => $this->video_id,
                'embed_url' => $embed_url,
                'still_image' => $image_url,
                'source' => $this->source,
                'auto_play' => $this->auto_play,
                'related_video' => $this->related_video,
                'video_info' => $this->video_info,
                'offset' => $this->offset,
                'both_visible' => $this->both_visible,
                'auto_hide' => $this->auto_hide,
                'auto_hide_progressbar' => $this->auto_hide_progressbar,
                'display_control' => $this->display_control,
                'display_control_caption' => $this->display_control_caption,
                'loop_video' => $this->loop,
                'show_annotation' => $this->show_annotation,
                'modest_branding' => $this->modest_branding,
            )
        );

        return $status;

    }
}