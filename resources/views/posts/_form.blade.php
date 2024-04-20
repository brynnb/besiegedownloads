
<div class="form-group">
    {!! Form::label('file','Select .bsg file to upload:',array('id'=>'','class'=>'')) !!}
    {!! Form::file('file',['class'=>'btn btn-primary']) !!}
    <i>This is the file Besiege makes in your SavedMachines folder.</i>
</div>
<br/>

<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class'=>'form-control', 'maxlength'=>'50', 'required'=>'required', 'placeholder'=>'Maximum 50 characters']) !!}
</div>
<br/>

<div class="form-group">
    {!! Form::label('description', 'Machine Description and Controls:') !!}
    {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Maximum 4000 characters', 'maxlength'=>'4000']) !!}
</div>
<br/>
<b>Machine Type:</b>
<div class="form-group">


    {!! Form::radio('type','1',false,array('id'=>'type1')) !!}
    {!! Form::label('type1', 'Air (planes, bombers)') !!}
    <br/>
    {!! Form::radio('type','2',false,['id'=>'type2']) !!}
    {!! Form::label('type2', 'Ground (anything with wheels that does not shoot stuff)') !!}
    <br/>
    {!! Form::radio('type','3',false,['id'=>'type3']) !!}
    {!! Form::label('type3', 'Shooting (primarily shoots stuff and does not fly)') !!}
    <br/>
    {!! Form::radio('type','5',false,['id'=>'type5']) !!}
    {!! Form::label('type5', 'Walking (anything that walks)') !!}
    <br/>
    {!! Form::radio('type','4',false,['id'=>'type4']) !!}
    {!! Form::label('type4', 'Contraption (anything not designed to destroy)') !!}

</div>
<br/>
<div class="form-group">
    {!! Form::label('url', 'Youtube video URL:') !!}
    {!! Form::text('url', null, ['class'=>'form-control', 'placeholder'=>'e.g. https://www.youtube.com/watch?v=dQw4w9WgXcQ']) !!}
    <i>If this is not an in-game video of your machine, or contains any NSFW content, it will be removed. </i>
</div>
<br/>
<div class="form-group">
    {!! Form::label('image_file','Image of machine (recommended at least 700 pixels wide, .jpg and .png only, max size 5MB):',array('id'=>'','class'=>'')) !!}
    {!! Form::file('image_file',array('id'=>'','class'=>'')) !!}
    <i>If this is not an in-game image of your machine, it will be removed.</i>
</div>
<br/>
