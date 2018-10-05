@push('modals')
    <div class="modal fade" id="modal-categories" tabindex="-1" role="dialog" aria-labelledby="modalCategoriesLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="title">
                        Category selection
                    </h4>
                </div>

                <div class="modal-body" style="overflow-y: scroll;height: 500px;">
                    <input type="hidden" id="modal-task-id" value="0">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="list-group">
                                @foreach($_categories as $cat)
                                    <a href="#"
                                       style="cursor: default;"
                                       onclick="alert('Выберите подкатегорию!')"
                                       onmouseover="showSubCategories('{{ $cat->id }}')"
                                       class="list-group-item {{isset($job) && $job->hasCategory($cat, true) ? 'active' : ''}}">

                                        <span class="badge">&gt;</span>
                                        {{$cat->nameEu}}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        @foreach($_categories as $cat)
                            <div class="col-xs-6 subcategories"
                                 style="{{(isset($job) && $job->hasCategory($cat, true)) ? '' : 'display: none;'}}"
                                 id="subcategories-{{ $cat->id }}">

                                <div class="list-group">
                                    @foreach($cat->subcategories as $subcat)
                                        <a href="#"
                                           onclick="setCategory('{{ $subcat->id }}', '{{ $cat->nameEu . " & " . $subcat->nameEu }}')"
                                           class="list-group-item {{(isset($job) && $job->hasCategory($subcat)) ? 'active' : ''}}">
                                            {{$subcat->nameEu}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush