import java.io.IOException;
import java.util.StringTokenizer;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.lib.chain.ChainMapper; 
import org.apache.hadoop.mapreduce.lib.chain.ChainReducer;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;

public class ChainMapperDriver {

 public static class SkipPunctuation
       extends Mapper<Object, Text, Text, Text>{

         private Text word = new Text();

    @Override
    public void map(Object key, Text value, Context context
                    ) throws IOException, InterruptedException {
      String line = value.toString();
      line = line.replaceAll("[^a-zA-Z]", " ");

        context.write(word, new Text(line));
    }
  }


 public static class TokenizerMapper
       extends Mapper<Text, Text, Text, IntWritable>{
    
    private final static IntWritable one = new IntWritable(1);
    private Text word = new Text();
 
    public void map(Text key, Text value, Context context
                    ) throws IOException, InterruptedException {
  
    String line = value.toString();
    StringTokenizer itr = new StringTokenizer(line);
    while(itr.hasMoreTokens()){
    word.set(itr.nextToken());
    context.write(word,one);
  }
}}

public static class IntSumReducer
       extends Reducer<Text,IntWritable,Text,IntWritable> {
    private IntWritable result = new IntWritable();

    public void reduce(Text key, Iterable<IntWritable> values,
                       Context context
                       ) throws IOException, InterruptedException {
      int sum = 0;
      for (IntWritable val : values) {
        sum += val.get();
      }
      result.set(sum);
      context.write(key, result);
    }
  }



public static void main(String [] args) throws Exception
{

Configuration conf = new Configuration();
Job job = Job.getInstance(conf,"Chain");
Configuration splitMapConfig = new Configuration(false);
ChainMapper.addMapper(job, SkipPunctuation.class,Object.class, Text.class, Text.class, Text.class, splitMapConfig);
Configuration lowerCaseConfig = new Configuration(false);
ChainMapper.addMapper(job,TokenizerMapper.class,Text.class, Text.class, Text.class, IntWritable.class, lowerCaseConfig);
job.setJarByClass(ChainMapperDriver.class);
Configuration reduceConfig = new Configuration(false);
ChainReducer.setReducer(job,IntSumReducer.class,Text.class,IntWritable.class,Text.class,IntWritable.class,reduceConfig);

job.setOutputKeyClass(Text.class);
job.setOutputValueClass(IntWritable.class);

FileInputFormat.addInputPath(job, new Path(args[0])); 
FileOutputFormat.setOutputPath(job, new Path(args[1]));
System.exit(job.waitForCompletion(true)?0:1);
}
} 
