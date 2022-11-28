<h1>Using UI Components Inside Widgets</h1>

## What are the benefits?
- You can use UI components inside widgets instead of limited functionality of widgets.
- You don't need to worry about data storage as the data is automatically stored inside the widgets.
- Easy to configure.

![](demo.gif)

## Example
You can see an example of a widget with most of the available UI components. The widget is called "[Example Widget with UI Components](../../../etc/widget.xml)". 

## How to use it?
- Create [app/code/Vendor/Module/etc/widget.xml](../../../etc/widget.xml) file inside your module.
    - You have to add only one parameter ```block``` with class ```Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components```.
    - ```namespace``` is the name of your form.xml file.
  ```xml
     <parameters>
            <parameter name="component_data" xsi:type="block">
                <block class="Grasch\AdminUi\Block\Adminhtml\Widget\Ui\Components">
                    <data>
                        <item name="namespace" xsi:type="string">widget_example_form</item>
                    </data>
                </block>
            </parameter>
        </parameters>
  ```
- Create [app/code/Vendor/Module/view/adminhtml/ui_component/form.xml](../../../view/adminhtml/ui_component/widget_example_form.xml) file inside your module. Add the UI Components that you need here.
    - Use this class ```Grasch\AdminUi\DataProvider\Widget\DataProvider``` as ```dataProvider``` for your form.
