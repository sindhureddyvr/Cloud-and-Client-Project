import java.io.IOException;
import java.util.StringTokenizer;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.io.NullWritable;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;

public class ListErrorPages{

  public static class TokenizerMapper
       extends Mapper<Object, Text, Text, NullWritable>{

    //private final static IntWritable one = new IntWritable(1);
    private Text word = new Text();
//    private NullWritable nw = new NullWritable();
    public void map(Object key, Text value, Context context
                    ) throws IOException, InterruptedException {
	String line = value.toString();
        if(line.charAt(0) != '#'){
	String[] sp = line.split("\\s+");
        if((sp[10].equals("404")) && (!sp[4].equals("/"))){
        word.set(sp[4]);
        context.write(word, NullWritable.get());
      }
    }}
  }


  public static void main(String[] args) throws Exception {
    Configuration conf = new Configuration();
    Job job = Job.getInstance(conf, "ip frequency");
    job.setJarByClass(FrequentIP.class);
    job.setMapperClass(TokenizerMapper.class);
    job.setOutputKeyClass(Text.class);
    job.setOutputValueClass(NullWritable.class);
    FileInputFormat.addInputPath(job, new Path(args[0]));
    FileOutputFormat.setOutputPath(job, new Path(args[1]));
    System.exit(job.waitForCompletion(true) ? 0 : 1);
  }
}
