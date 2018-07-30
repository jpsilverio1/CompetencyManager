


  <div class="competence-subtree">
   <ul>
    <li>
     <a href="{{ route('competences.show', $competence->id) }}">{{$competence->name}}</a>
     @if (count($competence->children) > 0)
      <ul>
       @foreach ($competence->children as $competenceChild)
        <li>
         <a href="{{ route('competences.show', $competenceChild->id) }}">{{$competenceChild->name}}</a>
         @if (count($competenceChild->children) > 0)
          <ul>
           @foreach ($competenceChild->children as $competenceGrandChild)
            <li>
             <a href="{{ route('competences.show', $competenceGrandChild->id) }}">{{$competenceGrandChild->name}}</a>
            </li>
           @endforeach
          </ul>
         @endif
        </li>
       @endforeach
      </ul>
      @else
      Esta competência não possui subcompetências.
     @endif
    </li>
   </ul>

  </div>
