{meta_xul css 'chrome://global/skin/'}
{meta_xul css '/jelix/xul/jxulform.css'}
{meta_xul css '/jelix/design/xulpage.css'}
{meta_xul css '/jelix/xul/jxbl.css'}
{meta_xul ns array('jxf'=>'jxulform', 'jx'=>'http://jelix.org/ns/xbl/1.0')}

<script type="application/x-javascript"><![CDATA[
  {literal}



  {/literal}
]]></script>

<commandset id="xuladmin-cmd-set">
    <command id="cmdx_grp_rename" />
    <command id="cmdx_grp_suppr" {if $groups->rowCount() <=1}disabled="true"{/if} />
    <command id="cmdx_grp_new" />
</commandset>



<description class="title-page">Gestion des droits</description>
<hbox>
    <menulist id="grouplist">
        <menupopup>
            {foreach $groups as $grp}
            <menuitem label="{$grp->name|escxml}" value="{$grp->id_aclgrp}"/>
            {/foreach}
        </menupopup>
    </menulist>

    <button label="Nouveau groupe" />

</hbox>

<!--
<vbox flex="1">
  <hbox flex="1">

  </hbox>

  <hbox>

  </hbox>
</vbox>-->
<hbox flex="1">
    <tabbox flex="1">
        <tabs>
            <tab label="Droits" />
            <tab label="Utilisateurs" />
            <tab label="Propri�t�s" />
        </tabs>
        <tabpanels flex="1">
            <tabpanel>
                <tree id="rights" flex="1" flags="dont-build-content" ref="urn:data:row" datasources="rdf:null"
                    onselect="" seltype="single"
                    >
                    <treecols>
                        <treecol id="subject-col" label="Sujets" primary="true" flex="1"
                                class="sortDirectionIndicator" sortActive="false"
                                sortDirection="ascending"
                                sort="rdf:http://jelix.org/ns/rights#subject"/>
                        <splitter class="tree-splitter"/>
                        <treecol id="res-col" label="Ressources" flex="1"
                                 class="sortDirectionIndicator" sortActive="true"
                                 sortDirection="ascending"
                                 sort="rdf:http://jelix.org/ns/rights#res"/>
                        <splitter class="tree-splitter"/>
                        <treecol id="values-col" label="Droits" flex="1"
                                class="sortDirectionIndicator" sortActive="true"
                                sortDirection="ascending"
                                sort="rdf:http://jelix.org/ns/rights#values"/>
                    </treecols>
                    <template>
                        <treechildren>
                            <treeitem uri="rdf:*">
                                <treerow>
                                    <treecell label="rdf:http://jelix.org/ns/rights#subject"/>
                                    <treecell label="rdf:http://jelix.org/ns/rights#res"/>
                                    <treecell label="rdf:http://jelix.org/ns/rights#values"/>
                                </treerow>
                            </treeitem>
                        </treechildren>
                    </template>
                </tree>
            </tabpanel>
            <tabpanel>
                <tree id="users" flex="1" flags="dont-build-content" ref="urn:data:row" datasources="rdf:null"
                    onselect="" seltype="single"
                    >
                    <treecols>
                        <treecol id="logins-col" label="Logins" primary="true" flex="1"
                                class="sortDirectionIndicator" sortActive="false"
                                sortDirection="ascending"
                                sort="rdf:http://jelix.org/ns/usersgroup#login"/>
                    </treecols>
                    <template>
                        <treechildren>
                            <treeitem uri="rdf:*">
                                <treerow>
                                    <treecell label="rdf:http://jelix.org/ns/usersgroup#login"/>
                                </treerow>
                            </treeitem>
                        </treechildren>
                    </template>
                </tree>

            </tabpanel>
            <tabpanel>
                <button label="Renommer" />
             <button label="Supprimer" />
            </tabpanel>
        </tabpanels>
    </tabbox>
    <vbox id="rightsedit"> <!--  collapsed="true" -->
        <groupbox submit="rightdata">
            <caption label="�dition des droits"/>

                <jxf:submission id="rightsform" action="jsonrpc.php5" method="POST"
                                format="json-rpc" rpcmethod="acl~"
                                onsubmit=""
                                onresult=""
                                onhttperror="alert('erreur http :' + event.errorCode)"
                                oninvalidate="alert('Saisissez correctement le login et l\'email')"
                                onrpcerror="alert(this.jsonResponse.error.toSource())"
                                onerror="alert(this.httpreq.responseText);"
                                />
                <checkbox label="foo" />
                <checkbox label="bar" />
                <checkbox label="baz" />
                <jxf:submit id="rightdata" form="rightsform" label="Sauvegarder"/>
        </groupbox>
        <groupbox>
            <caption label="�dition des droits"/>
            <vbox submit="rightdata2">

                <jxf:submission id="rightsform2" action="jsonrpc.php5" method="POST"
                                format="json-rpc" rpcmethod="acl~"
                                onsubmit=""
                                onresult=""
                                onhttperror="alert('erreur http :' + event.errorCode)"
                                oninvalidate="alert('Saisissez correctement le login et l\'email')"
                                onrpcerror="alert(this.jsonResponse.error.toSource())"
                                onerror="alert(this.httpreq.responseText);"
                                />
                <radiogroup>
                <radio label="foo" />
                <radio label="bar" />
                <radio label="baz" />
            </radiogroup>
                <jxf:submit id="rightdata2" form="rightsform2" label="Sauvegarder"/>
            </vbox>
        </groupbox>

    </vbox>
</hbox>